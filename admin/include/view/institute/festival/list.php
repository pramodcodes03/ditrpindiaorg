<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
?>

<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Festivals Section
          <a href="page.php?page=add-festival" class="btn btn-primary" style="float: right">Add Festivals</a>
        </h4>

        <div class="table-responsive pt-3">
          <table id="order-listing" class="table">
            <thead>
              <tr class="tableRowColor">
                <th>Sr.</th>
                <th>Festival Name</th>
                <th>Date</th>
                <!--<th>Images</th>-->
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once('include/classes/festival.class.php');
              $festival = new festival();

              $res = $festival->list_festival('', '', '');
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  extract($data);
                  $doc = $festival->get_docs_all($id, true);

                  if ($db->permission('update_course'))
                    $action = "<a href='page.php?page=update-festival&id=$id' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";
                  if ($db->permission('delete_course'))
                    $action .= "<a href='javascript:void(0)' onclick='deleteFestival($id)' class='btn btn-danger btn-sm' title='Delete'><i class=' fa fa-trash'></i>Delete</a>
                    					";


                  echo " <tr id='id" . $id . "'>
                    							<td>$srno</td>	
                    							<td>$name</td>
                    							<td>$date</td>
                    							
                    							<td>$action</td>
                                               </tr>";
                  $srno++;
                }
              }

              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>