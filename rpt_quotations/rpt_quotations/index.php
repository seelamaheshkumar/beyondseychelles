<?php include("../layouts/header.php"); ?>
<main id="main" class="main">
<div class="pagetitle row">
    <div class="col-md-12">
    <h1>Quotations</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Quotations</li>
        </ol>
    </nav>
</div>

</div>
    <section class="section dashboard">
 
<div class="row">
    <div class="col-lg-12">
          <div class="card">
          <!--  <button type="button" class="btn ripple btn-warning ms-auto" data-bs-target="#addModal" data-bs-toggle="modal" style="float:right;">
                            <span><i class="fa fa-plus"></i></span> New User </button>-->
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
    
    <!-- Update Status Modal -->
   <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Convert Quotation to Job </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form class="row g-3" name="frmustatus" id="frmustatus" method="post">
                            <div class="col-md-12">
                                <label>Quotation # Name</label>
                              <input type="text" id="qid" name="qid" class="form-control" readonly>
                            </div>
                            <div class="col-md-12">
                                <label>Company Name</label>
                              <input type="text" id="company_name" name="company_name" class="form-control" readonly>
                            </div>
                            <div class="col-md-12">
                                <label>Contact Name</label>
                              <input type="text" id="contact_person" name="contact_person" class="form-control" readonly>
                            </div>  
                              <div class="col-md-6">
                                <label>Qty</label>
                              <input type="text" id="tqty" name="tqty" class="form-control" readonly>
                            </div>  
                              <div class="col-md-6">
                                <label>Value</label>
                              <input type="text" id="qvalue" name="qvalue" class="form-control" readonly>
                            </div>  
                            <div class="col-md-6">
                                <label>Credit Bill</label>
                              <select name="credit_bill" id="credit_bill" class="form-control" required>
                                  <option value="No" selected>No</option>
                                  <option value="Yes">Yes</option>
                              </select>
                            </div>
                                                        <div class="col-md-6">
                                <label>PO No</label>
                              <input type="text" id="po_no" name="po_no" class="form-control" required>
                            </div>
                             <div class="col-md-6" style="text-align:right">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div> 
                            <div class="col-md-6" style="text-align:right">
                                <button type="submit" class="btn btn-success">Convert To Job</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                      
                    </div>
                  </div>
                </div>
              </div>       
    
  </main>
<?php include("../layouts/footer.php"); ?>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

