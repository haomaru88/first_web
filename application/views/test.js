var chartData = generateChartData();

function generateChartData() {
  var chartData = [];
  var firstDate = new Date( 2012, 0, 1 );
  firstDate.setDate( firstDate.getDate() - 1000 );
  firstDate.setHours( 0, 0, 0, 0 );

  for ( var i = 0; i < 1000; i++ ) {
    var newDate = new Date( firstDate );
    newDate.setHours( 0, i, 0, 0 );

    var a = Math.round( Math.random() * ( 40 + i ) ) + 100 + i;
    var b = Math.round( Math.random() * 100000000 );

    chartData.push( {
      "date": newDate,
      "value": a,
      "volume": b
    } );
  }
  return chartData;
}

var chart = AmCharts.makeChart( "chartdiv", {

  "type": "stock",
  "theme": "light",

  "categoryAxesSettings": {
    "minPeriod": "mm"
  },

  "dataSets": [ {
    "color": "#b0de09",
    "fieldMappings": [ {
      "fromField": "value",
      "toField": "value"
    }, {
      "fromField": "volume",
      "toField": "volume"
    } ],

    "dataProvider": chartData,
    "categoryField": "date"
  } ],


  "panels": [ {
      "titles": [ {
        "text": "Panel title: "
      } ],
      "showCategoryAxis": false,
      "title": "Value",
      "percentHeight": 70,

      "stockGraphs": [ {
        "id": "g1",
        "valueField": "value",
        "type": "smoothedLine",
        "lineThickness": 2,
        "bullet": "round"
      } ],


      "stockLegend": {
        "markerType": "none"
      }
    },

    {
      "title": "Volume",
      "percentHeight": 30,
      "stockGraphs": [ {
        "valueField": "volume",
        "type": "column",
        "cornerRadiusTop": 2,
        "fillAlphas": 1
      } ],

      "stockLegend": {
        "markerType": "none"
      }
    }
  ],

  "chartScrollbarSettings": {
    "graph": "g1",
    "usePeriod": "10mm",
  },

  "chartCursorSettings": {
    "valueBalloonsEnabled": true
  },

  "periodSelector": {
    "position": "bottom",
    "dateFormat": "YYYY-MM-DD JJ:NN",
    "inputFieldWidth": 150,
    "periods": [ {
      "period": "hh",
      "count": 1,
      "label": "1 hour",
      "selected": true

    }, {
      "period": "hh",
      "count": 2,
      "label": "2 hours"
    }, {
      "period": "hh",
      "count": 5,
      "label": "5 hour"
    }, {
      "period": "hh",
      "count": 12,
      "label": "12 hours"
    }, {
      "period": "MAX",
      "label": "MAX"
    } ]
  },

  "panelsSettings": {
    "usePrefixes": true
  }
} );

chart.addListener( "rendered", function( e ) {
  chart.addListener( "zoomed", function( e ) {
    var dates = [
      AmCharts.formatDate( e.startDate, "YYYY-MM-DD HH:NN" ),
      AmCharts.formatDate( e.endDate, "YYYY-MM-DD HH:NN" )
    ];

    // Avoid double callings
    clearTimeout( chart.panelUpdater );
    chart.panelUpdater = setTimeout( function() {
      for ( i1 in e.chart.panels ) {
        var panel = e.chart.panels[ i1 ];
        if ( panel.titles.length ) {
          panel.titles[ 0 ].text = dates.join( " - " );
          panel.validateData();
        }
      }
    }, 0 );
  } );
} );