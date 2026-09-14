<?php
require_once('../pdf/tcpdf.php');
require_once '../viewjob/viewjobModel.php';
$customersModel = new JobsModel();

class PDF extends TCPDF
{
    public $isLastPage = false;

    // Page header
    public function Header()
    {
        // Logo
        $imageFile = K_PATH_IMAGES.'topbg.jpg';
        $this->Image($imageFile, 0, 0, 210, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 16);
        $this->Ln(15);  // Line break after the header
    }

    // Page footer
    public function Footer()
    {
        if ($this->isLastPage) {
            $this->SetY(-55);
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(190, 10, 'Bank Details', 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->Ln(10);
            $this->SetFont('helvetica', '', 10);
            $this->Cell(185, 5, "Acc Name: BEYOND DESIGN", 0, 1, 'L');
            $this->Cell(185, 5, "Acc No: 010 1021858", 0, 1, 'L');
            $this->Cell(185, 5, "IBAN: SC96BARC01010000000101021858SCR", 0, 1, 'L');
            $this->Cell(185, 5, "SWIFT CODE: BARCSCSC", 0, 1, 'L');
            $this->Cell(185, 5, "Bank Name: ABSA", 0, 1, 'L');
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(190, 10, 'THANK YOU', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
}

// Create PDF object
$pdf = new PDF();
$pdf->SetCreator('S Mahesh Kumazr');
$pdf->SetAuthor('Credge Technologies');
$pdf->SetTitle('Invoice');
$repValue = isset($_GET['JobID']) ? $_GET['JobID'] : (isset($_GET['id']) ? $_GET['id'] : 0);

// Add a page
$pdf->AddPage();
$pdf->ln(30);
$quotation  = $customersModel->getJobDetailsById($repValue);
$products = $customersModel->showJobList($repValue);

if ($quotation) {
    $qdate = date('d-M-Y', strtotime($quotation['orderdate']));
   // $jobid = $quotation['jobid'] . '/' . date('Y');
    $jobid = $quotation['jobno'];
    
    // Start content immediately after the header
    $pdf->Ln(25);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(185, 8, "Invoice No # : " . $jobid, 0, 1, 'R');
    $pdf->Cell(185, 8, "TIN # : 352 891 692", 0, 1, 'R');
    if ($quotation['po_no'] !== '-' && $quotation['po_no'] !== '') {
        $pdf->Cell(185, 8, "Ref PO No # : " . $quotation['po_no'], 0, 1, 'R');
    }
    $pdf->Cell(185, 8, "Date # : " . $qdate, 0, 1, 'R');
    
    $pdf->Cell(185, 8, "CUSTOMER", 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(33, 8, "Company Name", 0, 0, 'L');
    $pdf->Cell(157, 8, ": " . $quotation['company_name'], 0, 1, 'L');
    $pdf->Cell(33, 8, "Contact Name", 0, 0, 'L');
    $pdf->Cell(157, 8, ": " . $quotation['contact_person'], 0, 1, 'L');
    $pdf->Cell(33, 8, "Contact No", 0, 0, 'L');
    $pdf->Cell(157, 8, ": " . $quotation['contact_no'], 0, 1, 'L');
    $pdf->Cell(33, 8, "Remarks", 0, 0, 'L');
    $pdf->Cell(157, 8, ": " . $quotation['address'], 0, 1, 'L');
    $pdf->Ln(15);
    
    // Table header
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(15, 8, "SL No", 1, 0);
    $pdf->Cell(85, 8, "Description", 1, 0);
    $pdf->Cell(25, 8, "Qty", 1, 0, 'C');
    $pdf->Cell(25, 8, "Unit Cost", 1, 0, 'C');
    $pdf->Cell(40, 8, "Amount", 1, 1, 'C');
    
    // Product data
    $pdf->SetFont('helvetica', '', 12);
    $slno = 1;
    foreach ($products as $product) {
        $maxLines = max(
            getNumLines($pdf, 85, $product['product_name']),
            getNumLines($pdf, 25, $product['qty']),
            getNumLines($pdf, 25, $product['unitcost']),
            getNumLines($pdf, 40, $product['total'])
        );
        $lineHeight = 8;
        $cellHeight = $maxLines * $lineHeight;

        $pdf->Cell(15, $cellHeight, $slno++, 'LTRB', 0, 'C');
        $pdf->MultiCell(85, $cellHeight, $product['product_name'], 'LTRB', 'L', 0, 0);
        $pdf->Cell(25, $cellHeight, $product['qty'], 'LTRB', 0, 'C');
        $pdf->Cell(25, $cellHeight, number_format($product['unitcost'], 2), 'LTRB', 0, 'C');
        $pdf->Cell(40, $cellHeight, number_format($product['total'], 2), 'LTRB', 1, 'R');

        // Check if the next item fits on the current page
        if ($pdf->GetY() + $cellHeight + 35 > $pdf->getPageHeight()) {
            $pdf->AddPage();
            $pdf->Ln(50); // Ensure there is space after the header on new pages
        }
    }

    // Subtotal, VAT, and totals
    $pdf->Cell(150, 8, "Sub Total", 0, 0, 'R');
    $pdf->Cell(40, 8, number_format($quotation['ovalue'], 2), 'LTRB', 1, 'R');

    if ($quotation['discount'] > 0) {
        $pdf->Cell(150, 8, "(-) Discount", 0, 0, 'R');
        $pdf->Cell(40, 8, number_format($quotation['discount'], 2), 'LTRB', 1, 'R');
        $pdf->Cell(150, 8, "Gross Total", 0, 0, 'R');
        $pdf->Cell(40, 8, number_format($quotation['ovalue'] - $quotation['discount'], 2), 'LTRB', 1, 'R');
    }

    if ($quotation['vat'] > 0) {
        $pdf->Cell(150, 8, "VAT 15%", 0, 0, 'R');
        $pdf->Cell(40, 8, number_format($quotation['vat'], 2), 'LTRB', 1, 'R');
    }

    $pdf->Cell(150, 8, "Net Total", 0, 0, 'R');
    $pdf->Cell(40, 8, number_format($quotation['netvalue'], 2), 'LTRB', 1, 'R');
    $pdf->Cell(150, 8, "Paid Amount", 0, 0, 'R');
    $pdf->Cell(40, 8, number_format($quotation['paid_amount'], 2), 'LTRB', 1, 'R');
    $pdf->Cell(150, 8, "Balance", 0, 0, 'R');
    $pdf->Cell(40, 8, number_format($quotation['balance'], 2), 'LTRB', 1, 'R');

    // Mark this as the last page for the footer
    $pdf->isLastPage = true;
}

// Close and output PDF
$pdf->Output('Invoice.pdf', 'I');

// Helper function to get the number of lines
function getNumLines($pdf, $width, $text)
{
    return $pdf->getNumLines($text, $width);
}


function truncateText($text, $maxLength) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength - 3) . '...';
    }
    return $text;
}

function numberTowords($num)
{

$ones = array(
0 =>"ZERO",
1 => "ONE",
2 => "TWO",
3 => "THREE",
4 => "FOUR",
5 => "FIVE",
6 => "SIX",
7 => "SEVEN",
8 => "EIGHT",
9 => "NINE",
10 => "TEN",
11 => "ELEVEN",
12 => "TWELVE",
13 => "THIRTEEN",
14 => "FOURTEEN",
15 => "FIFTEEN",
16 => "SIXTEEN",
17 => "SEVENTEEN",
18 => "EIGHTEEN",
19 => "NINETEEN",
"014" => "FOURTEEN"
);
$tens = array( 
0 => "ZERO",
1 => "TEN",
2 => "TWENTY",
3 => "THIRTY", 
4 => "FORTY", 
5 => "FIFTY", 
6 => "SIXTY", 
7 => "SEVENTY", 
8 => "EIGHTY", 
9 => "NINETY" 
); 
$hundreds = array( 
"HUNDRED", 
"THOUSAND", 
"MILLION", 
"BILLION", 
"TRILLION", 
"QUARDRILLION" 
); /*limit t quadrillion */
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr,1); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){
    
while(substr($i,0,1)=="0")
        $i=substr($i,1,5);
if($i < 20){ 
/* echo "getting:".$i; */
$rettxt .= $ones[$i]; 
}elseif($i < 100){ 
if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
}else{ 
if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
} 
if($key > 0){ 
$rettxt .= " ".$hundreds[$key]." "; 
}
} 
if($decnum > 0){
$rettxt .= " and ";
if($decnum < 20){
$rettxt .= $ones[$decnum];
}elseif($decnum < 100){
$rettxt .= $tens[substr($decnum,0,1)];
$rettxt .= " ".$ones[substr($decnum,1,1)];
}
}
return $rettxt;
}