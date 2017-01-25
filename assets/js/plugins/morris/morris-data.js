$(function() {

    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2014-03',
            normal: 150,
            salad: 50,
            vege: 200
        }, {
            period: '2014-04',
            normal: 200,
            salad: 100,
            vege: 150
        }, {
            period: '2014-05',
            normal: 200,
            salad: 80,
            vege: 210
        }, {
            period: '2014-06',
            normal: 250,
            salad: 110,
            vege: 210
        }, {
            period: '2014-07',
            normal: 280,
            salad: 150,
            vege: 250
        }, {
            period: '2014-08',
            normal: 280,
            salad: 170,
            vege: 250
        /* }, {
            period: '2011 Q3',
            normal: 4820,
            ipad: 3795,
            itouch: 1588
        }, {
            period: '2011 Q4',
            normal: 15073,
            ipad: 5967,
            itouch: 5175
        }, {
            period: '2012 Q1',
            normal: 10687,
            ipad: 4460,
            itouch: 2028
       }, {
            period: '2012 Q2',
            normal: 8432,
            ipad: 5713,
            itouch: 1791*/
        }],
        xkey: 'period',
        ykeys: ['normal', 'salad', 'vege'],
        labels: ['Normal Box', 'Salad Box', 'Vegetables Box'],
        pointSize: 2,
        hideHover: 'auto',
		smooth: false,
        resize: true
    });

    Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "Download Sales",
            value: 12
        }, {
            label: "In-Store Sales",
            value: 30
        }, {
            label: "Mail-Order Sales",
            value: 20
        }],
        resize: true
    });

    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 90
        }, {
            y: '2007',
            a: 75,
            b: 65
        }, {
            y: '2008',
            a: 50,
            b: 40
        }, {
            y: '2009',
            a: 75,
            b: 65
        }, {
            y: '2010',
            a: 50,
            b: 40
        }, {
            y: '2011',
            a: 75,
            b: 65
        }, {
            y: '2012',
            a: 100,
            b: 90
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        hideHover: 'auto',
        resize: true
    });

});
