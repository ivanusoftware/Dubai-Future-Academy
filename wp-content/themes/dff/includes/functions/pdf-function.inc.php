<?php
// For generating pdf
// require(get_template_directory() . '/includes/fpdf/fpdf.php');
// require(get_template_directory() . '/includes/fpdf/fpdf.php');
require_once(get_template_directory() . '/includes/tcpdf/tcpdf.php');
// function pixel_to_pt($px)
// {
//     return round(0.75 * $px);
// }

// function pt_to_pixel($pt)
// {
//     return round(4 * $pt / 3);
// }

// Create a certificate for a participant.
function make_participation_certificate($name, $title_en, $title_ar, $cource_id, $user_id, $cat_name_en, $cat_name_ar)
{
    $certificate_template = esc_url(get_template_directory_uri()) . "/images/pdf-certificate1.png";
    $slug = get_post_field('post_name', $cource_id);
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // $pdf->SetFont($fontname, '', 14, '', false);
    $pdf->SetFont('dejavusans', '', 14);
    $pdf->SetTextColor(245, 245, 245);
    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    // set margins
    $pdf->SetMargins(0, 0, 0, true);
    // set auto page breaks false
    $pdf->SetAutoPageBreak(false, 0);

    // add a page
    $pdf->AddPage('L', 'A4');

    // Display image on full page
    $pdf->Image($certificate_template, 0, 0, 297, 210, 'PNG', '', '', true, 200, '', false, false, 0, false, false, true);
    // Print a text
    // $pdf->SetXY(15, 20);
    // $html = '<span style="color:white;font-size:12px; width: 300px;">' . $title_en . '</span>';

    // $html = '<h2 style="font-size:12px; width: 300px;">' . $title_en . '</h2>';
    // Course category EN
    $pdf->SetXY(29, 75);
    $pdf->SetFont('dejavusans', '', 18);
    $pdf->MultiCell(55, 18, strtoupper($cat_name_en . ' CERTIFICATE'), 0, 'L', 0);
    // Course category AR
    $pdf->SetXY(-143, 75);
    $pdf->SetFont('dejavusans', '', 20);
    $pdf->MultiCell(55, 20, strtoupper($cat_name_ar . ' شهادة'), 0, 'R', 0);

    // User Name EN
    $pdf->SetXY(29, 119);
    $pdf->SetFont('dejavusans', '', 14);
    $pdf->MultiCell(65, 18, strtoupper($name), 0, 'L', 0);
    // User Name AR
    $pdf->SetXY(-153, 119);
    $pdf->SetFont('dejavusans', '', 14);
    $pdf->MultiCell(65, 18, strtoupper($name), 0, 'R', 0);
    // Course title EN
    $pdf->SetXY(28, 142);
    $pdf->SetFont('dejavusans', '', 11);
    $pdf->MultiCell(
        $w = 75,
        $h = 16,
        $txt = '"' . $title_en . '"',
        $border = array('LTRB' => array('width' => 0.1)),
        $align = 'L',
        $fill = 0,
        $ln = 10,
        $x = '',
        $y = '',
        $reseth = true,
        $stretch = 0,
        $ishtml = false,
        $autopadding = true,
        $maxh = $h,
        $valign = 'L',
        $fitcell = true
    );
    // Course title AR
    $pdf->SetXY(-163, 142);
    $pdf->SetFont('dejavusans', '', 11);
    $pdf->MultiCell(
        $w = 75,
        $h = 16,
        $txt = '"' . $title_ar . '"',
        $border = array('RTRB' => array('width' => 0.1)),
        $align = 'R',
        $fill = 0,
        $ln = 10,
        $x = '',
        $y = '',
        $reseth = true,
        $stretch = 0,
        $ishtml = false,
        $autopadding = true,
        $maxh = $h,
        $valign = 'L',
        $fitcell = true
    );
    // $pdf->writeHTML($html, true, false, true, false, '');

    //Close and output PDF document
 
    $path = $_SERVER['DOCUMENT_ROOT'];
    $filename = $path . '/wp-content/uploads/certificates/' . 'U' . $user_id . '_' . $slug . '.pdf';
    $url = '/wp-content/uploads/certificates/' . 'U' . $user_id . '_' . $slug . '.pdf';
    if (file_exists($filename)) {
        $url_file = $url;
    } else {
        $pdf->Output($path . '/wp-content/uploads/certificates/' . 'U' . $user_id . '_' . $slug  . '.pdf', 'F');
    $url_file = $url;
    }
    // ob_end_clean();
    // $pdf->Output($path . '/wp-content/uploads/certificates/' . 'U' . $user_id . '_' . $slug  . '.pdf', 'F');
    // $url_file = $url;

    return $url_file;
   
    // // echo "<script>window.location.href='$url';</script>";
}
