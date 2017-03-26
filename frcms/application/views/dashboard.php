<!DOCTYPE html>
<html lang="en">

<?php $this->load->view('head');?>

<body>
	<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-toggle"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse">
			
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="panel panel-info">
				<div class="panel-heading"><center><strong>Food Recognizer Statistics</center></div>
				<div class="panel-body">
					<div id="container" style="width: 100%;">
                    <div id="chart"></div>

    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="<?php echo base_url().'assets/js/c3.js'; ?>"></script>
    <script>
        var stats = null;
        //alert('<?php echo base_url() .'index.php/api/statistics'; ?>');

        $.getJSON( "<?php echo base_url() .'index.php/api/statistics'; ?>", function( data ) {
            stats = data;
            //alert(data[0].datetime);
        });

        //var chart = c3.generate(stats);
        setTimeout(function() {
            var chart = c3.generate({
                data: {
                json: stats,
                keys: {
                    x: 'datetime',
                    value: [ "total" ]
                }
                },
                axis: {
                x: {
                    type: "category"
                }
                }
            });
        }, 1000);
/*
    setTimeout(function () {
      chart = c3.generate({
        data: {
          json: [{
            "date": "2014-06-03",
             "443": "3000",
             "995": "500"
          }, {
            "date": "2014-06-04",
             "443": "1000",
          }, {
            "date": "2014-06-05",
             "443": "5000",
             "995": "1000"
          }],
          keys: {
            x: 'date',
            value: [ "443", "995" ]
          }
        },
        axis: {
          x: {
            type: "category"
          }
        }
      });
    }, 1000);

    setTimeout(function () {
      chart = c3.generate({
        data: {
//          x: 'name',
          json: [
            { id: 1, name: 'abc', visits: 200 },
            { id: 2, name: 'efg', visits: 400 },
            { id: 3, name: 'pqr', visits: 150 },
            { id: 4, name: 'xyz', visits: 420 },
          ],
          keys: {
            x: 'name',
            value: ['visits'],
          }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        }
      });
    }, 2000);

    setTimeout(function () {
      chart.load({
          json: [
            { id: 1, name: 'abc', visits: 1200 },
            { id: 2, name: 'efg', visits: 900 },
            { id: 3, name: 'pqr', visits: 1150 },
            { id: 4, name: 'xyz', visits: 1020 },
          ],
          keys: {
            x: 'name',
            value: ['visits'],
          }
      });
    }, 3000);
*/
    </script>
                    </div>
				</div>
				<div class="panel-footer"><center>&copy; GO-JEK. All right reserved.</center></div>
			</div>
		</div>
	</div>
</body>
</html>
