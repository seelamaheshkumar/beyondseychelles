<?php include("../layouts/header.php"); 
date_default_timezone_set('Indian/Mahe');
?>
<style>
    .align-right {
    text-align: right;
}
</style>
<main id="main" class="main">
<div class="pagetitle row">
    <div class="col-md-12">
    <h1>Cash Flow</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Day Wise Cash Flow</li>
        </ol>
    </nav>
</div>
</div>
    <section class="section dashboard">
 
<div class="row">
    <div class="col-lg-12">
          <div class="card"><br>
            <!--  <form name="frmsearch" id="frmsearch" method="post">
        <table class="table">
            <tr style="vertical-align:middle">
                <td>From</td>
                <td><input type="date" class="form-control" name="fdate" id="fdate"  required /></td>
                <td>To</td>
                <td><input type="date" class="form-control" name="tdate" id="tdate" required /></td>
                <td></td>
                <td> <button type="submit" class='btn btn-warning'>
                     <i class=" ri-file-search-fill"></i> Search</button></td>
            </tr>
        </table>
        </form> -->
       
                    <div class="card-body">
                        <div class="table-responsive">
                            <br>
                            <div class="table-responsive" id="showUsers">
                                <h3 class="text-center text-success" style="margin-top: 150px">Loading...</h3>
                                 
                       
                            </div>
                        </div>
                    </div>
          </div>
      </div>
    </section>
  </main>
      
<?php include("../layouts/footer.php"); ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">


<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="scripts.js"></script>
