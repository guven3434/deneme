<?php

final class TplParser
{

	private $content;
	private $sFile;
  private $sFileAlt = null;
	private $sBlock;
	private $startBlock = '<!-- BEGIN ';
	private $endBlock = '<!-- END ';
  private $endBlockLine = ' -->';
  private $aFilesContent;	
  private $sBlockContent;
  private $bEmbedPHP = null;
  private $sBlockPrefix = null;
  private $bDeletePrefixIfNotExists = true;
  private $sBlockWithoutPrefix = null;
  private $sDir;
  private $bTrim = true;
  private $aVariables;
  private static $oInstance = null;

  public static function getInstance( $sDir = null, $bEmbedPHP = null ){  
    if( !isset( self::$oInstance ) ){  
      self::$oInstance = new TplParser( $sDir, $bEmbedPHP );  
    }  
    return self::$oInstance;  
  } // end function getInstance

  /**
  * Constructor
  * @return void
  * @param string $sDir
  * @param bool   $bEmbedPHP
  */
  private function __construct( $sDir, $bEmbedPHP ){
    $this->setEmbedPHP( $bEmbedPHP );
    $this->setDir( $sDir );
  } // end function __construct
	
  /**
   *
  * Set variables
  * @return void
  * @param string $sName
  * @param mixed  $mValue
  */
  public function setVariables( $sName, $mValue ){
    $this->aVariables[$sName] = $mValue;
  } // end function setVariables

  /**
  * Set prefix block
  * @return void
  * @param string $sName
  * @param bool $bDeletePrefixIfNotExists
  */
  public function setPrefixBlock( $sName, $bDeletePrefixIfNotExists = true ){
    $this->sBlockPrefix = $sName;
    $this->bDeletePrefixIfNotExists = $bDeletePrefixIfNotExists;
  } // end function setPrefixBlock

  /**
  * Unset variables
  * @return void
  */
  public function unsetVariables( ){
    $this->aVariables = null;
  } // end function unsetVariables
  
  /**
  * Display parsed file
  * @return void
  * @param string $sFile - file *.tpl
  * @param bool   $bTrim
  */
	public function dHtml( $sFile, $bTrim = true ){
		$this->setFile( $this->sDir.$sFile );
    $this->bTrim = $bTrim;

		$this->display( );
    echo $this->content;
	} // end function dHtml

  /**
  * Return parsed file
  * @return string
  * @param string $sFile - file *.tpl
  * @param bool   $bTrim
  
  */
	public function tHtml( $sFile, $bTrim = true ){
		$this->setFile( $this->sDir.$sFile );
    $this->bTrim = $bTrim;

		$this->display( );
		return $this->content;
	} // end function tHtml

  /**
  * Display parsed sBlock from file
  * @return void
  * @param string $sFile - file *.tpl
  * @param string $sBlock
  * @param bool   $bTrim
  */
	public function dbHtml( $sFile, $sBlock, $bTrim = true ){
		$this->setFile( $this->sDir.$sFile );
		$this->setBlock( $sBlock );
    $this->bTrim = $bTrim;

		$this->display( true );
    echo $this->content;
	} // end function dbHtml


  public function loadTemplateFile($filename)
  {
  if (!file_exists($filename)) {
  throw new \Exception("Template file '{$filename}' not found");
  }

  $templateContent = file_get_contents($filename);
  $this->templateString = $templateContent;

  // Add this line to set the "short_open_tag" directive to true
  ini_set('short_open_tag', '1');
  }

 //  * Return parsed sBlock from file
 // * @return string
 // * @param string $sFile - file *.tpl
 // * @param string $sBlock
//  * @param bool   $bTrim
//  */
	public function tbHtml( $sFile, $sBlock, $bTrim = true ){
		$this->setFile( $this->sDir.$sFile );
		$this->setBlock( $sBlock );
    $this->bTrim = $bTrim;

		$this->display( true );
		return $this->content;
	} // end function tbHtml

  /**
  * function execute functions depend by parameter
  * @return void
  * @param bool $bBlock [optional]
  */
	public function display( $bBlock = null ){
		if( $this->checkFile( ) ){
			if( isset( $bBlock ) )
				$this->blockParse( );
			else
				$this->allParse( );
		}
	} // end function display
	
	/**
  * function check if file exists
  * @return boolean
  */
	private function checkFile( ){
		if( is_file( $this->sFile ) ){
	  	return true;
	  }
		else {
      $this->content = null;
      if( isset( $this->sFileAlt ) && is_file( $this->sDir.$this->sFileAlt ) ){
        $this->setFile( $this->sDir.$this->sFileAlt );
        return true;
      }
      else{
        echo 'ERROR - NO TEMPLATE FILE <b>'.$this->sFile.'</b><br />';
        return null;
      }
		}
	} // end function checkFile

  /**
  * Parse content with PHP
  * @return void
  */
  private function parsePHP( ){
    extract( $GLOBALS );
    while( $iPosition1 = strpos( $this->content, '<?php' ) ){
      $iPosition2 = strpos( $this->content, '?>' );
      $sPhpCode = substr( $this->content, $iPosition1 + 5, $iPosition2 - $iPosition1 - 5 );
      ob_start( );
      eval( $sPhpCode );
      $this->content = substr( $this->content, 0, $iPosition1 ).ob_get_contents( ).substr( $this->content, $iPosition2 + 2  );
      ob_end_clean( );
    } // end while
  } // end function parsePHP 
	
  /**
  * function parse $this->content
  * @return boolean
  */
	private function parse( ){
    if( isset( $this->bEmbedPHP ) && $this->bEmbedPHP === true && preg_match( '/<?php/', $this->content ) )
      $this->parsePHP( );

    preg_match_all( '/(\$[a-zA-Z_]+[a-zA-Z0-9_]*)(([\[]+[\']*[a-zA-Z0-9_]+[\']*[\]]+)*)/', $this->content, $aResults );
    if( isset( $aResults[1] ) && is_array( $aResults[1] ) ){
      $iCount = count( $aResults[1] );
      for( $i = 0; $i < $iCount; $i++ ){
        $aResults[1][$i] = substr( $aResults[1][$i], 1 );
        if( isset( $this->aVariables[$aResults[1][$i]] ) )
          $$aResults[1][$i] = $this->aVariables[$aResults[1][$i]];
        else
			
		$variableName = $aResults[1][$i];
        global $$variableName;
        //global $$aResults[1][$i];

        // array
        if( isset( $aResults[2] ) && !empty( $aResults[2][$i] ) ){
          if( preg_match( '/\'/', $aResults[2][$i] ) ){
            $aResults[2][$i] = str_replace( '\'', null, $aResults[2][$i] );
            $sSlash = '\'';
          }
          else
            $sSlash = null;

          preg_match_all( '/[a-zA-Z_\'0-9]+/', $aResults[2][$i], $aResults2 );
          $iCount2 = count( $aResults2[0] );
          if( $iCount2 == 2 ){
            if( isset( ${$aResults[1][$i]}[$aResults2[0][0]][$aResults2[0][1]] ) )
              $aReplace[] = ${$aResults[1][$i]}[$aResults2[0][0]][$aResults2[0][1]];
            else
              $aReplace[] = null;
            $aFind[] = '/\$'.$aResults[1][$i].'\['.$sSlash.$aResults2[0][0].$sSlash.'\]\['.$sSlash.$aResults2[0][1].$sSlash.'\]/';
          }
          else{
            if( isset( ${$aResults[1][$i]}[$aResults2[0][0]] ) )
              $aReplace[] = ${$aResults[1][$i]}[$aResults2[0][0]];
            else
              $aReplace[] = null;
            $aFind[] = '/\$'.$aResults[1][$i].'\['.$sSlash.$aResults2[0][0].$sSlash.'\]/';
          }
        }
        else{
          if( !is_array( $$aResults[1][$i] ) ){
            $aReplace[] = $$aResults[1][$i].'\\1';
            $aFind[] = '/\$'.$aResults[1][$i].'([^a-zA-Z0-9])/';
          }
        }
      } // end for
    }

    if( isset( $aFind ) )
      $this->content = preg_replace( $aFind, $aReplace, $this->content );
    if( isset( $this->bTrim ) )
      $this->content = trim( $this->content );
    return true;
		
	} // end function parse
	
  /**
  * function return all data from file
  * @return void
  */
	private function allParse( ){
    $this->content = $this->getContent( );
		$this->parse( );
	} // end function allParse
	
	// global değişken isimleri
 $globalVarName = $aResults[1][$i];
if (isset($this->aVariables[$globalVarName])) {
  $$globalVarName = $this->aVariables[$globalVarName];
} else {
  global $$globalVarName;
  $globalVarName = null;
  }

array
if (isset($aResults[2]) && !empty($aResults[2][$i])) {
  $aResults2 = [];
  preg_match_all('/[a-zA-Z_\'0-9]+/', $aResults[2][$i], $aResults2);
  $iCount2 = count($aResults2[0]);
  if ($iCount2 == 2) {
    $varName = $aResults[1][$i];
    if (isset($$varName[$aResults2[0][0]][$aResults2[0][1]])) {
      $aReplace[] = $$varName[$aResults2[0][0]][$aResults2[0][1]];
    } else {
      $aReplace[] = null;
    }
    $aFind[] = '/\$' . $varName . '\[\'' . $aResults2[0][0] . '\']\[\''. $aResults2[0][1] . '\'\]/';
  } else {
    $varName = $aResults[1][$i];
    if (isset($$varName[$aResults2[0][0]])) {
      $aReplace[] = $$varName[$aResults2[0][0]];
    } else {
      $aReplace[] = null;
    }
    $aFind[] = '/\$' . $varName . '\[\'' . $aResults2[0][0] . '\']/';
  }
} else {
  $varName = $aResults[1][$i];
  if (!is_array($$varName)) {
    $aReplace[] = $$varName . '\\1';
    $aFind[] = '/\$' . $varName .
  /**
  * Get defined sBlock from file
  * @return boolean
  */
	private function blockParse( ){
    if( isset( $this->sBlockContent[$this->sFile][$this->sBlock] ) )
      $this->content = $this->sBlockContent[$this->sFile][$this->sBlock];
    else{
      $this->content = $this->getFileBlock( );
      if( isset( $this->content ) ){
        $this->sBlockContent[$this->sFile][$this->sBlock] = $this->content;
      }
    }
    $this->parse( );
	} // end function blockParse

  /**
  * Get file data from file or from variable ($this->aFilesContent)
  * @return array
  * @param bool $bBlock
  */
  public function getContent( $bBlock = null ){
    if( isset( $this->aFilesContent[$this->sFile] ) )
      return $this->aFilesContent[$this->sFile];
    else
      return $this->aFilesContent[$this->sFile] = $this->getFile( $this->sFile );
  } // end function getContent

  /**
  * Return sBlock from file
  * @return string
  * @param string $sFile
  * @param string $sBlock
  * @param bool   $bAnotherTry
  */
  public function getFileBlock( $sFile = null, $sBlock = null, $bAnotherTry = null ){
    if( isset( $sFile ) && isset( $sBlock ) ){
      $this->setFile( $sFile );
      $this->setBlock( $sBlock, $bAnotherTry );
    }

    $sFile = $this->getContent( true );

    $iStart = strpos( $sFile, $this->startBlock.$this->sBlock.$this->endBlockLine );
    $iEnd = strpos( $sFile, $this->endBlock.$this->sBlock.$this->endBlockLine );

    if( is_int( $iStart ) && is_int( $iEnd ) ){
      $iStart += strlen( $this->startBlock.$this->sBlock.$this->endBlockLine );
      return substr( $sFile, $iStart, $iEnd - $iStart );
    }
    else {
      if( isset( $this->bDeletePrefixIfNotExists ) && isset( $this->sBlockPrefix ) ){
          $this->setBlock( $this->sBlockWithoutPrefix, true );
          return $this->getFileBlock( );
      }
      else{
        if( isset( $this->sFileAlt ) && is_file( $this->sDir.$this->sFileAlt ) ){
          $this->setFile( $this->sDir.$this->sFileAlt );       
          return $this->getFileBlock( $this->sFile, $sBlock );
        }
        else{
          echo 'No sBlock: <i>'.$this->sBlock.'</i> in file: '.$this->sFile.' <br />';
          return null;
        }
      }
    }
  } // end function getFileBlock

  /**
  * Return file content
  * @return string
  * @param string $sFile
  */
  public function getFile( $sFile ){
    return file_get_contents( $sFile );
  } // end function getFile

  /**
  * Return file to array
  * @return array
  * @param string $sFile
  */
  public function getFileArray( $sFile ){
    return file( $sFile );
  } // end function getFileArray

  /**
  * Return defined $this->sDir variable
  * @return string
  */
  public function getDir( ){
    return $this->sDir;
  } // end function getDir

  /**
  * function define $this->sDir variable
  * @return void
  * @param string $sDir
  */
  public function setDir( $sDir ){
    $this->sDir = $sDir;
  } // end function setDir

  /**
  * function define $this->bEmbedPHP variable
  * @return void
  * @param bool $bEmbed
  */
  public function setEmbedPHP( $bEmbed ){
    $this->bEmbedPHP = $bEmbed;
  } // end function setEmbedPHP

  /**
  * function define $this->sFile variable
  * @return void
  * @param string $sFile
  */
  public function setFile( $sFile ){
    $this->sFile = $sFile;
  } // end function setFile

  /**
  * function define $this->sFileAlt variable
  * @return void
  * @param string $sFileAlt
  */
  public function setFileAlt( $sFileAlt ){
    $this->sFileAlt = $sFileAlt;
  } // end function setFileAlt



  /**
  * function define $this->sBlock variable
  * @return void
  * @param string $sBlock
  * @param bool   $bAnotherTry
  */
  private function setBlock( $sBlock, $bAnotherTry = null ){
    if( isset( $this->sBlockPrefix ) && isset( $this->bDeletePrefixIfNotExists ) )
      $this->sBlockWithoutPrefix = $sBlock;

    if( isset( $bAnotherTry ) ){
      $this->sBlock = $sBlock;
      $this->sBlockWithoutPrefix = null;
    }
    else{
      $this->sBlock = $this->sBlockPrefix.$sBlock;
    }
  } // end function setBlock

}; // end class TplParser
?>