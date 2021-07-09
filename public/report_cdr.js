
$(function () {

    var json_data;

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    var start1 = 0;
    var calls_limit = '20';
    var search_number = '';
    var end1 = 0;

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    today = mm + '/' + dd + '/' + yyyy;

    function myRefresh() {
        calls_limit = $("#calls_limit").value;
        search_number = $("#search_number").value;
        $("#content").jsGrid("search");
    }

    $("#calls_limit").change(function () {
        calls_limit = this.value;
        $("#content").jsGrid("search");
    });

    $("#search_number").change(function () {
        search_number = this.value;
        $("#content").jsGrid("search");
    });

    $('#daterange').daterangepicker({
        "showDropdowns": true,
        "showWeekNumbers": true,
        "startDate": today,
        "endDate": today,
        onSelect: function (startDate, endDate) {
            $("#content").jsGrid("search");
        }
    },
        function (start, end, label) {
            start1 = start.format('YYYY-MM-DD');
            end1 = end.format('YYYY-MM-DD');
            $("#content").jsGrid("search");
        });

    $("#but_excel a").click(function () {
        window.location.replace("../db/report/cdr/download.php?start=" + start1 + "&end=" + end1 + "&calls_limit=" + calls_limit + "&search_number=" + search_number);
    });

    DATA = null;

    $("#content").jsGrid({
        height: "93%",
        width: "100%",
        filtering: false,
        inserting: false,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 200,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete row?",
        controller: {
            loadData: function (filter) {
                var start = start1;
                var end = end1;
                console.log(calls_limit);

                return $.ajax({
                    type: "GET",
                    url: "../db/report/cdr/?start=" + start + "&end=" + end + "&calls_limit=" + calls_limit + "&search_number=" + search_number,
                    success: function (data) {
                        json_data = data;  //store to global var for exporting
                        var counter = data.length;
                        var sbillsec_answer = 0;
                        var sbillsec_not = 0;
                        var cbillsec_answer = 0;
                        var cbillsec_not = 0;
                        var date = 0;
                        var day = 0;
                        var month = 0;
                        var year = 0;

                        for (var i = 0; i < counter; i++) {

                            if (data[i].whois_man) {
                                data[i].whois = data[i].whois_man;
                            }
                            if (data[i].disposition == "ANSWERED") {
                                sbillsec_answer += parseFloat(data[i].billsec);
                                cbillsec_answer += 1;
                            }
                            if (data[i].disposition != "ANSWERED") {
                                sbillsec_not += parseFloat(data[i].billsec);
                                cbillsec_not += 1;
                            }
                            if (data[i].clid != null) {
                                data[i].clid = data[i].clid.replace(/(\<(\/?[^>]+)>)/g, '');
                                data[i].clid = data[i].clid.replace(/['"']/g, '');
                            }
                            data[i].billsec = toMMSS(data[i].billsec);
                            data[i].duration = toMMSS(data[i].duration);

                            date = data[i].calldate;
                            day = myDay(date); //data[i].calldate.parse(mySQLDate.replace('-','/','g')).to;
                            month = myMonth(date); //data[i].calldate.dateparts[0];
                            year = myYear(date); //data[i].calldate.dateparts[0];
                            if (data[i].recordingfile) {
                                if (data[i].recordingfile[0] != "/") {
                                    data[i].recordingfile = year + "/" + month + "/" + day + "/" + data[i].recordingfile;
                                }
                            } else {

                            }
                        }

                        $("#total").text("Records: " + counter +
                            ", Answered " + cbillsec_answer + " (" + toMMSS(sbillsec_answer) + ")" +
                            ", no answer " + cbillsec_not);
                    }
                });
            }
        },
        fields: [
            { name: "calldate", title: "Дата", type: "date", width: 120, filtering: false },
            { name: "src", title: "Звонящий", type: "text", width: 100 },
            { name: "dst", title: "Вызываемый", type: "text", width: 100 },
            { name: "disposition", title: "Состояние", type: "text", width: 100 },
            { name: "duration", title: "Длительность звонка", type: "text", width: 100 },
            { name: "billsec", title: "Длительность разговора", type: "text", width: 100 },
        ],
        onDataLoaded: function (data) {
        },
        onError: function () {
            console.log("Ошибка: " + this.src);
        }

    });

    $("form").submit(function () {
        $("#content").jsGrid("refresh");
    });
});
