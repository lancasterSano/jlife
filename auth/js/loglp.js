    // $(document).ready(function(){
    //     // LOGER.waitForMsg();  Start the inital request 
    //     $('#startLog').on('click', function(){ 
    //         if(LOGER.work)
    //         {
    //             $(this).val('Start log');
    //             stop = true;
    //             LOGER.addmsg(0, 0, 0, "old", "... Stop log.");
    //         }
    //         else
    //         {
    //             $(this).val('Stop log');
    //             stop = false;
    //             // $("#messages").empty(); 
    //             LOGER.addmsg(0, 0, 0, "old", "Start log ...");
    //             LOGER.waitForMsg();
    //             // debugger;
    //         }
    //         LOGER.work = !LOGER.work;

    //     } );//.click();
    //     // $('#stopLog').on('click', function(){  } );
    // });

function tt () {
    alert('fun js in file');
}
var CH_STORAGE = {
    data: null,
}

LOGER = {
    work: false,
    LAST_ID : 0,
    _color: function(value) {
        var RGB = {R:0,G:0,B:0};

        // y = mx + b
        // m = 4
        // x = value
        // y = RGB._
        if (0 <= value && value <= 1/8) {
            RGB.R = 0;
            RGB.G = 0;
            RGB.B = 4*value + .5; // .5 - 1 // b = 1/2
        } else if (1/8 < value && value <= 3/8) {
            RGB.R = 0;
            RGB.G = 4*value - .5; // 0 - 1 // b = - 1/2
            RGB.B = 0;
        } else if (3/8 < value && value <= 5/8) {
            RGB.R = 4*value - 1.5; // 0 - 1 // b = - 3/2
            RGB.G = 1;
            RGB.B = -4*value + 2.5; // 1 - 0 // b = 5/2
        } else if (5/8 < value && value <= 7/8) {
            RGB.R = 1;
            RGB.G = -4*value + 3.5; // 1 - 0 // b = 7/2
            RGB.B = 0;
        } else if (7/8 < value && value <= 1) {
            RGB.R = -4*value + 4.5; // 1 - .5 // b = 9/2
            RGB.G = 0;
            RGB.B = 0;
        } else {    // should never happen - value > 1
            RGB.R = .5;
            RGB.G = 0;
            RGB.B = 0;
        }

        // scale for hex conversion
        RGB.R *= 15;
        RGB.G *= 15;
        RGB.B *= 15;

        return Math.round(RGB.R).toString(16)+''+Math.round(RGB.G).toString(16)+''+Math.round(RGB.B).toString(16);
    },
    addmsg: function (groupunic_id, task, number, type, msg){
        /* Simple helper to add a div.
        type is the name of a CSS class (old/new/error).
        msg is the contents of the div */
        var incr = 0;
        if(number<10) value = 0;
        else if(number<20) value = 1; 
        else if(number<30) value = 2;
        else if(number<40) value = 3;
        else if(number>=40) {
            value = 4;

            incr = number%40;
        }

        var padding = "padding-left:" + (10+value*10+incr*20) + "px;",
            background = number ? "background-color:#" + LOGER._color(task/8) + ";" : "",
            color = number ? "color:#" + LOGER._color(task/80)+";" : "",
            plh_groupunic_id = number ? "<b>" + groupunic_id + "</b>" : "",
            plh_task = number ? "<b>" + task + "</b>" : "",
            plh_number = number ? "<b>" + number + "</b>" : "";
        $("#messages").append(
            "<div class='msg "+ type +"' style='" + background + "'>" + plh_groupunic_id + plh_task +
                "<div style='display: -webkit-inline-box;" + padding + color + "'>" +
                    plh_number + msg +"</div></div>"
        );
    },
    waitForMsg: function (){
        if(stop) return;
        $.ajax({
            type: "GET",
            url: "/auth/loglp/list/last/"+LOGER.LAST_ID,
            async: true, /* If set to non-async, browser shows page as "Loading.."*/
            cache: false,
            timeout:50000, /* Timeout in ms */

            success: function(data){ /* called when request to barge.php completes */
                objRez = JSON.parse(data);
                objs = objRez['logrows']; // console.log('logrows : ' + objRez.lastID);
                LOGER.LAST_ID = objRez.lastID;
                // debugger
                var buffer = [];
                buffer[1] = []; buffer[2] = []; buffer[3] = []; buffer[4] = []; buffer[5] = []; buffer[6] = [];
                for(var key in objs)
                {
                    cur_task = objs[key];
                    cur_task_number = cur_task.ns_task;
                        // console.log(cur_task_number);
                    buffer[cur_task_number].push( cur_task );

                    switch(cur_task_number){
                        case '4':
                            if(cur_task.debug_backtrace != "")
                            {
                                LOGER.internalTaskEvoluation(cur_task);
                            }
                            break
                        default:
                            if(cur_task.debug_backtrace != "")
                            {
                                console.log(cur_task.nameopp);
                                console.log(JSON.parse(cur_task.debug_backtrace));
                            }
                            LOGER.addmsg(cur_task.ns_groupunic_id, cur_task_number, cur_task.number, "new", cur_task.nameopp); /* Add response to a .msg div (with the "new" class)*/
                    }
                }
                setTimeout( function () { LOGER.waitForMsg(); }, 4000 );
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                LOGER.addmsg(0, 0, 0, "error", textStatus + " (" + errorThrown + ")");
                setTimeout( function () { LOGER.waitForMsg(); }, 15000); }
        });
    },
    internalTaskEvoluation: function (obj) {
        console.log("INTERNAL:");
        var groupunic_id = obj.ns_groupunic_id,
            task = obj.ns_task,
            number = obj.number,
            type = "new",
            msg = obj.nameopp;

        var incr = 0;
            if(number<10) value = 0;
            else if(number<20) value = 1; else if(number<30) value = 2;
            else if(number<40) value = 3; else if(number>=40) { value = 4; incr = number%40; }

        var padding = "padding-left:" + (10+value*10+incr*20) + "px;",
            background = number ? "background-color:#" + LOGER._color(task/8) + ";" : "",
            color = number ? "color:#" + LOGER._color(task/80)+";" : "",
            plh_groupunic_id = number ? "<b>" + groupunic_id + "</b>" : "",
            plh_task = number ? "<b>" + task + "</b>" : "",
            plh_number = number ? "<b>" + number + "</b>" : "";

        $("#messages").append(
            "<div class='msg "+ type +"' style='" + background + "'>" + plh_groupunic_id + plh_task +
                "<div style='" + padding + color + "'>" +
                    plh_number + msg +"</div><div class='data'>" + LOGER.parseInternalTask(obj.debug_backtrace) + "</div></div>"
        );

        var table1 = $('#table1').tabelize({
            /*onRowClick : function(){
                alert('test');
            }*/
            fullRowClickable : false,
            onReady : function(){
                console.log('ready');
            },
            onBeforeRowClick :  function(){
                console.log('onBeforeRowClick');
            },
            onAfterRowClick :  function(){
                console.log('onAfterRowClick');
            },
        });

        $("table.controller tr td.data a").fancybox();
        // .fancybox({
        //         maxWidth    : 800,
        //         maxHeight   : 600,
        //         fitToView   : false,
        //         width       : '70%',
        //         height      : '70%',
        //         autoSize    : false,
        //         closeClick  : false,
        //         openEffect  : 'none',
        //         closeEffect : 'none'
        //     });
    },
    parseInternalTask: function (internalJSON) {
        internalObj = JSON.parse(internalJSON);
        console.log(internalObj);
        // G_D = [];
        // G_D['LEARNERS'] = [];
        // G_D['LEARNERS'][2] = 
        // debugger
        window.tmpl._tmpl_internal = tmpl($("#_tmpl_internal").html()),
        $("#messages").append( window.tmpl._tmpl_internal({title: "Ура, шаблонизатор работает!" }) );
        debugger
        return '<table id="table1" class="controller"> <tr data-level="header" class="header"><td></td><td>Column 1</td><td>Column 2</td><td>Column 3</td></tr> <tr data-level="1" id="level_1_a">  <td>Level 1 A</td><td class="data">2</td><td class="data">2</td>\
        <td class="data">\
            <a href="#inline1">2</a>\
            <div id="inline1" style="width:400px;display: none;"> <h3>Etiam quis mi eu elit</h3> <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam quis mi eu elit tempor facilisis id et neque. Nulla sit amet sem sapien. Vestibulum imperdiet porta ante ac ornare. Nulla et lorem eu nibh adipiscing ultricies nec at lacus. Cras laoreet ultricies sem, at blandit mi eleifend aliquam. Nunc enim ipsum, vehicula non pretium varius, cursus ac tortor. Vivamus fringilla congue laoreet. Quisque ultrices sodales orci, quis rhoncus justo auctor in. Phasellus dui eros, bibendum eu feugiat ornare, faucibus eu mi. Nunc aliquet tempus sem, id aliquam diam varius ac. Maecenas nisl nunc, molestie vitae eleifend vel, iaculis sed magna. Aenean tempus lacus vitae orci posuere porttitor eget non felis. Donec lectus elit, aliquam nec eleifend sit amet, vestibulum sed nunc. </p> </div>\
        </td>\
        </tr> <tr data-level="2" id="level_2_a">\
        <td>Level 2 A</td><td class="data">2</td><td class="data">2</td>\
        <td class="data"><a href="#">2</a></td></tr> <tr data-level="3" id="level_3_a">\
        <td>Level 3 A</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_a">  <td>Level 4 A</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_b">  <td>Level 4 B</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="3" id="level_3_b">  <td>Level 3 B</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_c">  <td>Level 4 C</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_d">  <td>Level 4 D</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_e">  <td>Level 4 E</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_f">  <td>Level 4 F</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_g">  <td>Level 4 G</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="1" id="level_1_b">  <td>Level 1 B</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="2" id="level_2_b">  <td>Level 2 B</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="3" id="level_3_c">  <td>Level 3 C</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_h">  <td>Level 4 H</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_i">  <td>Level 4 I</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_j">  <td>Level 4 J</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="3" id="level_3_d">  <td>Level 3 D</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_k">  <td>Level 4 K</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_l">  <td>Level 4 L</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> <tr data-level="4" id="level_4_m">  <td>Level 4 M</td><td class="data">2</td><td class="data">2</td><td class="data">2</td></tr> </table>';
        return internalObj;
    }

}

