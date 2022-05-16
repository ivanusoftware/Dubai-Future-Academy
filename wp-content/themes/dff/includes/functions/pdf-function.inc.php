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
function make_participation_certificate($name, $event, $cource_id, $user_id, $exam_date, $lenghts)
{
    $today = date("d M Y");
    $certificate_dimensions = array(pixel_to_pt(1123), pixel_to_pt(794));
    $name_pos = array(pixel_to_pt(240), pixel_to_pt(350));
    $name_size = array(pixel_to_pt(637), pixel_to_pt(0));
    // $event_pos = array(pixel_to_pt(520), pixel_to_pt(470));
    // $event_size = array(pixel_to_pt(221), pixel_to_pt(10));
    $certificate_template = esc_url(get_template_directory_uri()) . "/images/pdf-certificate.png";

    $certificate = new FPDF("Landscape", "pt", $certificate_dimensions);
    $certificate->AddPage();

    $certificate->Image($certificate_template, 0, 0, $certificate_dimensions[0], $certificate_dimensions[1]);
    $certificate->SetFont("Helvetica", "", 54);
    $certificate->SetXY($name_pos[0], $name_pos[1]);
    $certificate->Cell($name_size[0], $name_size[1], $name, 0, 0, "C");
    $certificate->SetFont("Helvetica", "", 16);
    $certificate->SetXY(220, 335);
    // $certificate->Cell($event_size[0], $event_size[1], $event, 0, 0, "C");
    // $certificate->Text(0,370, $event);
    $strText = $event;
    $strText = str_replace("\n", "<br>", $strText);
    $certificate->MultiCell(400, 25, $strText, 0, 'C', 0);

    // echo date('d M Y',strtotime($exam_date));

    $certificate->SetFont("Helvetica", "", 14);
    $certificate->Text(200, 520,  'Date: ' . date('d M Y',strtotime($exam_date)));
    $certificate->SetFont("Helvetica", "", 14);
    $certificate->Text(520, 520,  'Lenghts: '. $lenghts .' total hours');
    // $certificate->Ln(10);

    // $certificate->Output("I", "certificate.pdf");

    // $today = date("Y-m-d-G-i-s");
    $path = $_SERVER['DOCUMENT_ROOT'];
    $filename = $path . 'wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf';
    $url = '/wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf';
    if (file_exists($filename)) {
        $url_file = $url;
    } else {
        $certificate->Output('wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf', 'F');
        $url_file = $url;
    }
    // $certificate->Output('wp-content/uploads/certificates/' . $user_id . '_' . $cource_id . '_pdf_certificate.pdf', 'F');
    // $url_file = $url;

    return $url_file;
    // echo "<script>window.location.href='$url';</script>";
}
