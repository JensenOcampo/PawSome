<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
</head>

<body>
    <?php include_once('navbar.php'); ?>
    <div class="content py-5">
        <h3 class="">Our Services</h3>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <h3>Categories</h3>
                    <div class="list-group">
                        <div class="list-group-item list-group-item-action">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="category_all" value="all" <?= $cid == 'all' ? "checked" : "" ?>>
                                <label for="category_all" class="custom-control-label">All</label>
                            </div>
                        </div>
                        <?php
                        $cat_qry = $conn->query("SELECT * FROM `category_list` where delete_flag = 0");
                        while ($row = $cat_qry->fetch_assoc()):
                        ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input category-item" type="checkbox" id="category_<?= $row['id'] ?>" value="<?= $row['id'] ?>" <?= $cid == 'all' || in_array($row['id'], explode(',', $cid)) ? "checked" : "" ?>>
                                    <label for="category_<?= $row['id'] ?>" class="custom-control-label"><?= $row['name'] ?></label>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="list-group" id="service-list">
                        <?php
                        $categories = $conn->query("SELECT * FROM `category_list`");
                        $cat_arr = array_column($categories->fetch_all(MYSQLI_ASSOC), 'name', 'id');
                        $cwhere = "";
                        if ($cid != 'all') {
                            $cwhere .= " and ";
                            $_cw = "";
                            foreach (explode(',', $cid) as $v) {
                                if (!empty($_cw)) $_cw .= " or ";
                                $_cw .= "CONCAT('|',REPLACE(category_ids,',','|,|'),'|') LIKE '%|{$v}|%'";
                            }
                            $cwhere .= "({$_cw})";
                        }
                        $services = $conn->query("SELECT * FROM `service_list` where delete_flag = 0 {$cwhere}  order by `name` asc");
                        while ($row = $services->fetch_assoc()):
                            $for = '';
                            foreach (explode(',', $row['category_ids']) as $v) {
                                if (isset($cat_arr[$v])) {
                                    if (!empty($for)) $for .= ", ";
                                    $for .= $cat_arr[$v];
                                }
                            }
                            $for = empty($for) ? "N/A" : $for;
                        ?>
                            <div class="text-decoration-none list-group-item rounded-0 service-item">
                                <a class="d-flex w-100 text-dark align-items-center" href="#service_<?= $row['id'] ?>" data-toggle="collapse">
                                    <div class="col-11">
                                        <h3 class="mb-0"><b><?= ucwords($row['name']) ?></b></h3>
                                        <small><em>(<?= $for ?>)</em></small>
                                    </div>
                                    <div class="col-1 text-right">
                                        <i class="fa fa-plus collapse-icon"></i>
                                    </div>
                                </a>
                                <div class="collapse" id="service_<?= $row['id'] ?>">
                                    <hr class="">
                                    <div class="row align-items-top">
                                        <div class="col-10">
                                        </div>
                                        <div class="col-2 text-right">
                                            <div class="mx-3"><span class="fa fa-tags"></span> <?= number_format($row['fee'], 2) ?></div>
                                        </div>
                                    </div>
                                    <p class="mx-3"><?= html_entity_decode($row['description']) ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>