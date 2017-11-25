<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Spot Awards :: Doolally</title>
	<?php echo $globalStyle; ?>
<!--    <link rel="stylesheet" href="<?php /*echo base_url(); */?>asset/css/ui.jqgrid.css">
    <link rel="stylesheet" href="<?php /*echo base_url(); */?>asset/css/ui.jqgrid-bootstrap.css">
    <link rel="stylesheet" href="<?php /*echo base_url(); */?>asset/css/ui.jqgrid-bootstrap-ui.css">-->
</head>
<body>
    <?php echo $headerView; ?>
    <main class="logComplaint">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1 col-xs-0"></div>
                <div class="col-sm-10 col-xs-12">
                    <div class="row">
                        <?php
                        if(myInArray('spot_award_add',$userModules))
                        {
                            ?>
                            <a class="btn btn-primary" href="<?php echo base_url().'spotaward/addNewAwards';?>">
                                <i class="fa fa-plus"></i>
                                Add New Award List</a>
                            <?php
                        }
                        ?>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12 filter_row">
                            <ul class="list-inline">
                                <li>
                                    <input type="text" placeholder="Start Month" id="startMonth" class="form-control"/>
                                </li>
                                <li>
                                    <input type="text" placeholder="end Month" id="endMonth" class="form-control"/>
                                </li>
                                <li>
                                    <input type="text" placeholder="Enter Emp name/ID" id="empName" class="form-control"/>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-warning clear-tab-filter">Clear</button>
                                </li>
                            </ul>
                        </div>
                        <table id="new-spot-table" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Award Month</th>
                                <th>Emp ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Location</th>
                                <th>Reason</th>
                            </tr>
                            </thead>

                        </table>
                        <!--<div style="margin-left:20px;">
                            <table id="jqGrid"></table>
                            <div id="jqGridPager"></div>
                        </div>-->
                    </div>
                </div>
                <div class="col-sm-1 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<!--<script type="text/javascript" src="<?php /*echo base_url(); */?>asset/js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="<?php /*echo base_url(); */?>asset/js/grid.locale-en.js"></script>-->

<script>

    $('#startMonth,#endMonth').datetimepicker({
       format:'MM/YYYY'
    });
    var snames = [];
    var onlyone = false;
    var newTab = $('#new-spot-table').DataTable({
        ordering: false,
        deferRender: true,
        ajax: base_url+'spotaward/getSpotRecords',
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                if(column.index() == 3 || column.index() == 4 || column.index() == 5)
                {
                    var select = $('<select class="form-control"><option value="">All' +
                        '</option></select>')
                        .appendTo( $(column.header()) )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );

                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                }
            } );
        }
    });
    $('#new-spot-table_filter').addClass('hide');
    //$.jgrid.defaults.width = 780;
    /*$.jgrid.defaults.responsive = true;
    $.jgrid.defaults.styleUI = 'Bootstrap';

    $(document).ready(function () {
        $("#jqGrid").jqGrid({
            url: base_url+'spotaward/getSpotRecords',
            mtype: "GET",
            datatype: "json",
            page: 1,
            colModel: [
                {
                    label: "Award Date",
                    name: 'awardDate',
                    sorttype:'string',
                    width:100
                },
                {   label : "Emp ID",
                    sorttype: 'string',
                    name: 'empId',
                    key: true,
                    width:80
                },
                {
                    label: "Name",
                    name: 'empName',
                    sorttype:'string',
                    width:100
                },
                {
                    label: "Designation",
                    name: 'empDesignation',
                    sorttype:'string',
                    width:80
                },
                {
                    label: "Department",
                    name: 'empDepartment',
                    sorttype:'string',
                    width:80
                },
                {
                    label: "Location",
                    name: 'empLocation',
                    sorttype:'string',
                    width:80
                },
                {
                    label: "Reason",
                    name: 'reasonText',
                    sorttype:'string',
                    width:350
                }
            ],
            loadonce: true,
            viewrecords: true,
            height: 300,
            rowNum: 10,
            pager: "#jqGridPager"
        });
        // activate the build in search with multiple option
        $('#jqGrid').navGrid("#jqGridPager", {
                search: true, // show search button on the toolbar
                add: false,
                edit: false,
                del: false,
                refresh: true,
            },
            {}, // edit options
            {}, // add options
            {}, // delete options
            { multipleSearch: true,
                sopt: ['eq', 'cn', 'nc', 'bw', 'bn', 'ew','en'],
                defaultSearch: 'cn'
            } // search options - define multiple search
        );
    });*/

    $('#startMonth').on('dp.change', function(e){
        if($('#startMonth').val() != '' && $('#endMonth').val() != '')
        {
            newTab.draw();
        }
    });
    $('#endMonth').on('dp.change', function(e){
        if($('#startMonth').val() != '' && $('#endMonth').val() != '')
        {
            console.log('in');
            newTab.draw();
        }
    });

    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            if($('#startMonth').val() != '' && $('#endMonth').val() != '')
            {
                var min = $('#startMonth').val().split('/');
                var max = $('#endMonth').val().split('/');
                var age = $(newTab.column(0).data()[dataIndex]); // use data for the age column

                if($('#empName').val() != '')
                {
                    if(Number(age.attr('data-year')) >= Number(min[1]) && Number(age.attr('data-year')) <= Number(max[1]))
                    {
                        if((Number(age.attr('data-month')) >= Number(min[0]) && Number(age.attr('data-month')) <= Number(max[0])) &&
                            (newTab.column(2).data()[dataIndex].toLowerCase().indexOf($('#empName').val().toLowerCase()) !== -1 ||
                            newTab.column(1).data()[dataIndex].toLowerCase().indexOf($('#empName').val().toLowerCase()) !== -1))
                        {
                            return true;
                        }
                    }
                    else if(newTab.column(2).data()[dataIndex].toLowerCase().indexOf($('#empName').val().toLowerCase()) !== -1 ||
                        newTab.column(1).data()[dataIndex].toLowerCase().indexOf($('#empName').val().toLowerCase()) !== -1)
                    {
                        return true;
                    }
                }
                else
                {
                    if(Number(age.attr('data-year')) >= Number(min[1]) && Number(age.attr('data-year')) <= Number(max[1]))
                    {
                        if(Number(age.attr('data-month')) >= Number(min[0]) && Number(age.attr('data-month')) <= Number(max[0]))
                        {
                            return true;
                        }
                    }
                }
                return false;
            }
            else if($('#empName').val() != '')
            {
                if(newTab.column(2).data()[dataIndex].toLowerCase().indexOf($('#empName').val().toLowerCase()) !== -1 ||
                    newTab.column(1).data()[dataIndex].toLowerCase().indexOf($('#empName').val().toLowerCase()) !== -1)
                {
                    return true;
                }
                return false;
            }
            else
            {
                return true;
            }
        }
    );
    $(document).on('click','.clear-tab-filter',function(){
        $('#startMonth').data("DateTimePicker").clear();
        $('#endMonth').data("DateTimePicker").clear();
        $('#empName').val('');
        newTab.search('').draw();
    });
    $(document).on('keyup','#empName', function(){
        if($(this).val() != '')
        {
            newTab.draw();
        }
        else{
            newTab.search('').draw();
        }
    });
</script>
</html>