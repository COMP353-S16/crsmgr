<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
//header("Content-type: text/json");
$k = array();

$Group = new Group(62);
$GroupStats = new GroupStats($Group );
$stats = $GroupStats->getStats();


$cats = array();
$downloads = array();
$uploads = array();

$downsize = array();
$upsize = array();
foreach($stats['members'] as $i => $memberData)
{
    $cats[] = $memberData['name'];
    $downloads[] = $memberData['numberOfDownloads'];
    $uploads[] = $memberData["numberOfUploads"];
    $downsize[] = round($memberData['totalDownloadsSize'],1);
    $upsize[] = round($memberData['totalUploadsSize'],1);
}


$usedBandwith = $GroupStats->getUsedBandwidth();
$allowedBandwidth = $Group->getMaxUploadSize();


if($allowedBandwidth > 0 )
{
    $used = ($usedBandwith / $allowedBandwidth) * 100;
}
else
{
    $used = 0;
}

$free = 100 - $used;

?>




<div class="row">

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                General
            </div>
            <div class="panel-body">

                <table class="table">
                    <tr>
                        <td>Total files</td>
                        <td><?php echo number_format($GroupStats->getTotalFiles());?></td>
                    </tr>
                    <tr>
                        <td>Total uploads</td>
                        <td><?php echo number_format($GroupStats->getNbOfUploadedFiles());?></td>
                    </tr>
                    <tr>
                        <td>Total downloads (includes non-members)</td>
                        <td><?php echo $GroupStats->getNumberOfDownloads();?></td>
                    </tr>
                    <tr>
                        <td>Total deleted files</td>
                        <td><?php echo number_format($GroupStats->getTotalDeletedFiles());?></td>
                    </tr>
                    <tr>
                        <td>Total permanently deleted files</td>
                        <td><?php echo number_format($GroupStats->getTotalPermanentDelete());?></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Storage
            </div>
            <div class="panel-body">
                <div id="usedba"></div>
            </div>

        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                User Traffic
            </div>
            <div class="panel-body">
                <div id="ul_dl"></div>
            </div>

        </div>
    </div>


</div>








<script>

        $('#ul_dl').highcharts({

            credits: false,
            chart: {
                type: 'column'
            },
            title: {
                text: 'Downloads / Uploads'
            },
            xAxis: {
                categories: <?php echo json_encode($cats);?>,
                crosshair: true
            },
            /*
            yAxis: {
                min: 0,
                title: {
                    text: 'Files'
                }
            },*/
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Files',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            }, { // Secondary yAxis
                labels: {

                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                title: {
                    text: 'Megabytes',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },

                opposite: true
            }],

            tooltip: {

                shared: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                y: 10, // 10 pixels down from the top
            },
            series: [{
                name: 'Downloads',
                data: <?php echo json_encode($downloads);?>,
                yAxis: 0,

            }, {
                name: 'Uploads',
                data: <?php echo json_encode($uploads);?>,
                yAxis: 0,

            },
            {
                name: 'Total Downloads Size',
                type: 'column',
                yAxis: 1,
                data: <?php echo json_encode($downsize);?>,
                tooltip : {
                    valueSuffix : ' MB'
                }

            },
            {
                name: 'Total Uploads Size',
                type: 'column',
                yAxis: 1,
                data: <?php echo json_encode($upsize);?>,
                tooltip : {
                    valueSuffix : ' MB'
                }

            }


            ]
        });


        $('#usedba').highcharts({
            credits: {
                enabled: false
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
            },
            title: {
                text: 'Storage'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.3f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.3f} % of ' + <?php echo $allowedBandwidth; ?>+"MB",
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{

                colorByPoint: true,
                data: [{
                    name: 'Free',
                    y: <?php echo $free;?>
                }, {
                    name: 'Used',
                    y: <?php echo $used; ?>

                }]
            }]
        });


</script>
