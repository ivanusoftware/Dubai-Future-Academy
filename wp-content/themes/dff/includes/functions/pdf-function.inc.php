<?php
// For generating pdf
require(get_template_directory() . '/includes/fpdf/fpdf.php');

function pixel_to_pt($px)
{
    return round(0.75 * $px);
}

function pt_to_pixel($pt)
{
    return round(4 * $pt / 3);
}

// Create a certificate for a participant.
function make_participation_certificate($name, $event, $cource_id, $user_id, $cat_name, $exam_date, $lenghts)
{
    $today = date("d M Y");
    $certificate_dimensions = array(pixel_to_pt(1440), pixel_to_pt(810));
    $name_pos = array(pixel_to_pt(240), pixel_to_pt(350));
    $name_size = array(pixel_to_pt(637), pixel_to_pt(0));
    // $event_pos = array(pixel_to_pt(520), pixel_to_pt(470));
    // $event_size = array(pixel_to_pt(221), pixel_to_pt(10));
    $certificate_template = esc_url(get_template_directory_uri()) . "/images/pdf-certificate1.png";

    $certificate = new FPDF("Landscape", "pt", $certificate_dimensions);
    $certificate->AddPage();

    $certificate->Image($certificate_template, 0, 0, $certificate_dimensions[0], $certificate_dimensions[1]);
    $certificate->SetTextColor("255,255,255");
    // Taxonomy Name
    $certificate->SetFont("Helvetica", "", 24);
    $certificate->SetXY(105, 185);
    // $certificate->Cell(1, 0, strtoupper($cat_name), 0, 0, "L");
    $certificate->MultiCell(220, 32, strtoupper($cat_name . ' CERTIFICATE'), 0, 'L', 0);
    // Name Student
    $certificate->SetFont("Helvetica", "", 16);
    $certificate->SetXY(105, 355);
    $certificate->Cell(1, 0, strtoupper($name), 0, 0, "L");

    // Title course
    $certificate->SetFont("Helvetica", "", 12);
    $certificate->SetXY(105, 415);
    $strText = '"' . $event . '"';
    $certificate->MultiCell(270, 20, $strText, 0, 'L', 0);
    // $certificate->MultiCell(400,6,"Here's some text for display", 'LRT', 'L', 0);

    // echo date('d M Y',strtotime($exam_date));

    // $certificate->SetFont("Helvetica", "", 14);
    // $certificate->Text(200, 520,  'Date: ' . date('d M Y',strtotime($exam_date)));
    // $certificate->SetFont("Helvetica", "", 14);
    // $certificate->Text(520, 520,  'Lenghts: '. $lenghts .' total hours');
    // $certificate->Ln(10);

    // $certificate->Output("I", "certificate.pdf");

    // $today = date("Y-m-d-G-i-s");
    $path = $_SERVER['DOCUMENT_ROOT'];
    $filename = $path . 'wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf';
    $url = '/wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf';
    // if (file_exists($filename)) {
    //     $url_file = $url;
    // } else {
    //     $certificate->Output('wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf', 'F');
    //     $url_file = $url;
    // }
    $certificate->Output('wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf', 'F');
    $url_file = $url;

    return $url_file;
    // echo "<script>window.location.href='$url';</script>";
}
