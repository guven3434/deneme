<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Referans Düzenle</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <?php
                    if (isset($_POST["save"])) {
                        if ( $_POST[ "save" ] == 1453 ) {
                            $title = $_POST[ 'title' ];
                            $description = $_POST[ 'description' ];
                            $images = 'images/' . $_FILES[ 'images' ][ 'name' ];
                            $tarih = date ( 'Y-m-d H:i:s' );
                            $user_id = 1;
                            $durum = $_POST[ 'durum' ][ 0 ];
                            $images_temp = $_FILES[ 'images' ][ 'tmp_name' ];
                            if ( file_exists ( $images ) ) {
                                print '<div class="alert alert-danger">Aynı isimle dosya mevcuttur...</div>';
                                header ( "refresh:2;url=referans" );
                            } else {
                                move_uploaded_file ( $images_temp , $images );

                                $sql = "INSERT INTO referanslar (title, description, image, user_id, durum, tarih) VALUES (?, ?, ?, ?, ?, ?)";
                                $args = [ $title , $description , $images , $user_id , $durum , $tarih ];
                                $args = $adminclass->getsecure ( $args );
                                $query = $adminclass->pdoinsert ( $sql , $args );
                                if ( $query ) {
                                    print '<div class="alert alert-success">İşlem Başarılı</div>';
                                    header ( "refresh:2;url=referans" );
                                } else {
                                    print '<div class="alert alert-danger">İşlem Başarısız</div>';
                                    header ( "refresh:2;url=referans" );
                                }
                            }
                        }
                    }

                    if (isset($_GET['id']))
                    {
                       $id=$adminclass->getsecure ($_GET['id']);
                    ?>
                    <!-- general form elements -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Referans Düzenle</h3>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php print $id;?>>">
                            <?php
                            $sql = "SELECT * FROM referanslar id = {$id}";
                            $query = $adminclass->pdoQuery ($sql);
                            if ($query){
                                ?>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Başlık</label>
                                    <input type="text" class="form-control" name="title" value="<?php echo $query[0]['title'];?>">
                                </div>
                                <div class="form-group">
                                    <label>Açıklama</label>
                                    <textarea class="form-control" rows="5" name="description"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- select -->
                                        <div class="form-group">
                                            <label>Durum</label>
                                            <select class="form-control" name="durum[]">
                                                <option value="1">Aktif</option>
                                                <option value="2">Pasif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Resim Ekle</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="images">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                  }
                                  }
                            ?>
                            <input type="hidden" name="save" value="1453">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Güncelle</button>
                            </div>
                        </form>
                    </div>

    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>