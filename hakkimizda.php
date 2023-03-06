<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Hakkımızda</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <?php
    if (isset($_POST['save'])){
        if ($_POST['save']== 1453){
            $title=$adminclass->getsecure ($_POST['title']);
            $description=$adminclass->getsecure ($_POST['description']);
            $userid=$_POST['user_id'];
            $durum=$adminclass->getsecure ($_POST['durum'][0]);
            $sql="INSERT INTO hakkimizda(title, description, icerik, durum, user_id) VALUES (?,?,?,?,?)";
            $args=[$title,$description,'',$durum,$userid];
            $result = $adminclass->getsecure ($args);
            print $adminclass->pdoinsert($sql,$result);
        }
    }
if (isset($_POST['delete'])){
    $delete_id= $_POST['delete'];
    $sql=("SELECT * FROM hakkimizda where id = ?");
    $args=[$delete_id];
    $result=$adminclass->pdodelete($sql,$args);
    print $result;
}
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                                Yeni Ekle
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th>Açıklama</th>
                                    <th>İçerik</th>
                                    <th>Durum</th>
                                    <th>İşlem</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $veriable = $adminclass->getabout();
                                foreach ($veriable as $value){
                                    ?>
                                    <tr>
                                        <td><?php echo $value['id'];?></td>
                                        <td><?php echo $value['title'];?></td>
                                        <td><?php echo $value['description'];?></td>
                                        <td><?php echo $value['icerik'];?></td>
                                        <td><?php echo $value['durum'];?></td>
                                        <td>
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default<?php echo $value['id'];?>">
                                                SİL
                                            </button>
                                            <div class="modal fade" id="modal-default<?php echo $value['id'];?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hakkımızda | Sil...</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card card-success">
                                                                <div class="card-header">
                                                                </div>
                                                                <div class="card-body">
                                                                    <form method="POST">
                                                                        <input type="hidden" name="delete" value="<?php echo $value['id'];?>">
                                                                        <p><?php echo $value['title'];?>- Bölümünü Silmek istediğinizden Eminmisiniz ?</p>

                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-info" data-dismiss="modal">Vazgeç | Kapat</button>
                                                                            <button type="submit" class="btn btn-danger">SİL</button>
                                                                            <input type="hidden" name="user_id" value="1">
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th>Açıklama</th>
                                    <th>İçerik</th>
                                    <th>Durum</th>
                                    <th>İşlem</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hakkımızda | Yeni Ekle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-success">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Başlık</label>
                                        <input type="text" class="form-control" name="title" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label>Açıklama</label>
                                        <textarea class="form-control" rows="6" name="description"></textarea>
                                    </div>
                                </div>
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
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Vazgeç | Kapat</button>
                                <button type="submit" class="btn btn-success">Kayıt Et</button>
                                <input type="hidden" name="save" value="1453">
                                <input type="hidden" name="user_id" value="1">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>