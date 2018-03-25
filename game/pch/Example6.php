<?php
 // Standard inclusions   
 include("pChart/pData.class");
 include("pChart/pChart.class");

 // Dataset definition 
 $DataSet = new pData;
 $DataSet->ImportFromCSV("Sample/datawithtitle.csv",",",array(1,2,3),TRUE,0);
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie();

 // Initialise the graph
 $Test = new pChart(700,230);
 $Test->setFontProperties("tahoma.ttf",8);
 $Test->setGraphArea(60,30,680,200);
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
 $Test->drawGraphArea(255,255,255);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),5,150,150,150,TRUE,0,2);
 $Test->drawGrid(4,220,220,220);

 // Draw the 0 line
 $Test->setFontProperties("tahoma.ttf",6);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the filled line graph
 $Test->drawFilledLineGraph($DataSet->GetData(),$DataSet->GetDataDescription(),50);

 // Finish the graph
 $Test->setFontProperties("tahoma.ttf",8);
 $Test->drawLegend(65,35,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("tahoma.ttf",10);
 $Test->drawTitle(60,22,"Example 6",50,50,50,585);
 $Test->Render("Example6.png");
?>