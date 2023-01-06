<?php
include 'Database.php';
if (!Database::getConnection()) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
}
Database::getConnection()->query("SET NAMES 'utf8'");

session_start();
require('tfpdf/tfpdf.php');

class PDF extends tFPDF
{
    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extract attributes
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        // Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',11);

$user = $_SESSION['user'];
$test_id = $_POST['test_id'];
$user_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM users WHERE login='$user'"))[0];
$questions = mysqli_fetch_all(Database::getConnection()->query("SELECT * FROM questions WHERE test_id='$test_id' ORDER BY id asc"));

$points = 0;
foreach ($questions as $question) {
    $user_answers = [];
    if(isset($_POST["wybory_" . $question[0]])) {
        $user_answers = $_POST["wybory_" . $question[0]];
    }

    $point_to_add = false;
    foreach ($user_answers as $answer) {
        if (strpos($question[7], $answer) !== false) {
            $point_to_add = true;
        } else {
            $point_to_add = false;
            break;
        }
    }

    if ($point_to_add) {
        $points++;
    }
}

$pdf_name = $test_id . '_' . $user_id . '.pdf';
Database::getConnection()->query("INSERT INTO results (employee_id, test_id, points, pdf_file) VALUES ('$user_id', '$test_id', '$points', '$pdf_name')");
$pdf_date = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM results ORDER BY id desc LIMIT 1"))[3];
$result_id = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM results ORDER BY id desc LIMIT 1"))[0];

$pdf_name = $test_id . '_' . $user_id . '_' . $pdf_date . '.pdf';
Database::getConnection()->query("UPDATE results SET pdf_file='$pdf_name' WHERE id='$result_id'");


$pdf->WriteHTML($user);
$pdf->Ln();
$pdf->WriteHTML(mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM tests WHERE id='$test_id'"))[2]);
$pdf->Ln();
$pdf->WriteHTML($pdf_date);
$pdf->Ln();
$pdf->Ln();

foreach ($questions as $question) {
    $pdf->WriteHTML($question[2]);
    $pdf->Ln();
    $user_answers = [];
    if(isset($_POST["wybory_" . $question[0]])) {
        $user_answers = $_POST["wybory_" . $question[0]];
    }

    if (in_array("a", $user_answers)) {
        if (strpos($question[7], "a") !== false) {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(0, 255, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        } else {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        }
    } else {
        $pdf->SetFont('ZapfDingbats','',11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->WriteHTML(chr(111));
        $pdf->SetFont('Arial','',11);
        $pdf->WriteHTML(' ' . $question[3]);
        $pdf->Ln();
    }

    if (in_array("b", $user_answers)) {
        if (strpos($question[7], "b") !== false) {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(0, 255, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        } else {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        }
    } else {
        $pdf->SetFont('ZapfDingbats','',11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->WriteHTML(chr(111));
        $pdf->SetFont('Arial','',11);
        $pdf->WriteHTML(' ' . $question[4]);
        $pdf->Ln();
    }

    if (in_array("c", $user_answers)) {
        if (strpos($question[7], "c") !== false) {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(0, 255, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        } else {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        }
    } else {
        $pdf->SetFont('ZapfDingbats','',11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->WriteHTML(chr(111));
        $pdf->SetFont('Arial','',11);
        $pdf->WriteHTML(' ' . $question[5]);
        $pdf->Ln();
    }

    if (in_array("d", $user_answers)) {
        if (strpos($question[7], "d") !== false) {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(0, 255, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        } else {
            $pdf->SetFont('ZapfDingbats','',11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->WriteHTML(chr(53));
            $pdf->SetFont('Arial','B',11);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->WriteHTML(' ' . $question[3]);
            $pdf->Ln();
        }
    } else {
        $pdf->SetFont('ZapfDingbats','',11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->WriteHTML(chr(111));
        $pdf->SetFont('Arial','',11);
        $pdf->WriteHTML(' ' . $question[6]);
        $pdf->Ln();
    }

    $pdf->Ln();


}


$pdf->Output('F', 'pdf/' . $pdf_name);
$pdf->Output();