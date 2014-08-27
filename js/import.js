(function($) {
    // Attrs
    $.fn.attrs = function(attrs) {
        var t = $(this);
        if (attrs) {
            // Set attributes
            t.each(function(i, e) {
                var j = $(e);
                for (var attr in attrs) {
                    j.attr(attr, attrs[attr]);
                };
            });
            return t;
        } else {
            // Get attributes
            var a = {},
                r = t.get(0);
            if (r) {
                r = r.attributes;
                for (var i in r) {
                    var p = r[i];
                    if (typeof p.nodeValue !== 'undefined') a[p.nodeName] = p.nodeValue;
                }
            }
            return a;
        }
    };
})(jQuery);
function handleFileSelect(evt) {
	var files = evt.target.files; // FileList object

	// // files is a FileList of File objects. List some properties.
	// var output = [];
	// for (var i = 0, f; f = files[i]; i++) {
	//   output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
	//               f.size, ' bytes, last modified: ',
	//               f.lastModifiedDate.toLocaleDateString(), '</li>');
	// }
	// document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';

	//    var output_xml = [];
	//    // Loop through the FileList
	// for (var i = 0, f; f = files[i]; i++) {

	//   var reader = new FileReader();

	//   // Closure to capture the file information.
	//   reader.onload = (function(theFile) {
	//     return function(e) {
	//       // Print the contents of the file
	//       var span = document.createElement('span');                    
	//       span.innerHTML = ['<p>',e.target.result,'</p>'].join('');
	//       // output_xml.push();
	//       document.getElementById('list_xml_context').insertBefore(span, null);
	//     };
	//   })(f);

	//   // Read in the file
	//   //reader.readAsDataText(f,UTF-8);
	//   reader.readAsText(f,"UTF-8");
	//   // reader.readAsDataURL(f);
	// }
	var file = evt.target.files[0],
	    reader = new FileReader();

	waitForTextReadComplete(reader);
	reader.readAsText(file);
}

function parseTextAsXml(text) {
	var $log = $( "#log" ),
		// parser = new DOMParser(),
	// xmlDom = parser.parseFromString(text, "text/xml");
	xmlDoc = $.parseXML( text ),
	$xml = $(xmlDoc);
	idOfContainerDomElement = "log";
	// // nodeNames = [];
	// // Append the parsed HTML
	//     debugger;
	// $(text).find('note').each(function () {
	//        $log.append('' + $(this));
	//        // $log.append('<div class="book"><div class="title">' + $(this)
	//        // 	.find("Title").text() + '</div><div class="description">' + $(this)
	//        // 	.find("Description").text() + '</div><div class="date">Published ' + $(this)
	//        // 	.find("Date").text() + '</div></div>');
	//        // $(".book").fadeIn(1000);
	//     });
	// // $log.text( $xml );

	// // // Gather the parsed HTML's node names
	// // $.each( xml, function( i, el ) {
	// //   nodeNames[ i ] = "<li>" + el.nodeName + "</li>";
	// // });
	 
	// // // Insert the node names
	// // $log.append( "<h3>Node Names:</h3>" );
	// // $( "<ol></ol>" )
	// //   .append( nodeNames.join( "" ) )
	// //   .appendTo( $log );

	//     //now, extract items from xmlDom and assign to appropriate text input fields

	// try {
	  var objdata = traverseXmlDocToObject($xml, idOfContainerDomElement);
	  
	  $(document.getElementById('list_xml_context')).append(print_objdata(objdata, 0));
	  // debugger;


	  
	// } catch (err) {
	//     alert(err.message);
	// }
}

function print_objdata (objdata, level) {
	var view = $(document.createElement('div'));
	for(var propt in objdata){
		if(typeof objdata[propt] === "object") {
			view.append( drow_div_object(level, propt, objdata[propt]) )
				.append( print_objdata(objdata[propt], (level+1)) );
		}
		else // вершины графа
			view.append(drow_div_object(level,propt,objdata[propt]));
	}
	return view;
}

function drow_div_object (level, datakeym, datavalue) {
	if(!is_array(datavalue) && !is_object(datavalue)) {
		var rez = $('<div>', 
			{
				'css': 
				{'margin-left': level*10, 'margin-top': 0, 'margin-right': 0, 'margin-bottom': 0, },
				'html': datakeym + " : " + datavalue
			});
	} else {
		var rez = $('<div>', 
			{
				'css': 
				{'margin-left': level*10, 'margin-top': 0, 'margin-right': 0, 'margin-bottom': 0, },
				'html': datakeym
			});
	}

	return rez;
}

function waitForTextReadComplete(reader) {
	reader.onloadend = function(event) {
	var text = event.target.result;
	parseTextAsXml(text);
	}
}
function traverseXmlDocToObject(xmlDoc, idOfContainerDomElement, initialMarginLeft) {
	var $xmlDocObj, $xmlDocObjChildren, $contentDiv;
	$xmlDocObj = $(xmlDoc);
	$xmlDocObjChildren = $(xmlDoc).children();
	initialMarginLeft = (!is_numeric(initialMarginLeft)) ? 0 : initialMarginLeft += 20;

	var identify = 0, objRoot = {}, stack = [];

	$xmlDocObjChildren.each(function(index, Element) {
		var $currentObject = $(this),
			childElementCount = Element.childElementCount,
				currentNodeType = $currentObject.prop('nodeType'),
				currentNodeName = $currentObject.prop('nodeName'),
				currentTagName = $currentObject.prop('tagName'),
				currentTagText = $currentObject.text();

		if ( currentTagName.indexOf('spisok') !== 0 && childElementCount == 0) { // singe object
			obj = {};
			obj[currentTagName] = $currentObject.attrs();
			stack.push(obj);
		}
		else {
			// if(currentTagName.indexOf('spisok') == 0) {
				if(objRoot[currentTagName]===undefined) objRoot[currentTagName] = [];
				if (childElementCount > 0) { // есть дочернии
					var result = traverseXmlDocToObject($currentObject, idOfContainerDomElement, initialMarginLeft);
					if(currentTagName.indexOf('spisok') == 0) {
						objRoot[currentTagName].push(result);
					} else {
						objRoot[currentTagName] = result;						
					}
				} else {
					objRoot[currentTagName].push("empty");
				}
			// }
			// else {
			// 	if (childElementCount > 0) { // есть дочернии
			// 		objRoot[currentTagName] = {};			
			// 		var result = traverseXmlDocToObject($currentObject, idOfContainerDomElement, initialMarginLeft);
			// 		if(result !== null)
			// 			objRoot[currentTagName] = result;
			// 	} else {
			// 		objRoot[currentTagName].push("empty_");
			// 	}
			// }
		}
	});
	return stack.length ? stack : objRoot;
}
function is_numeric(mixed_var) {
		// Returns true if value is a number or a numeric string
		//
		// version: 1109.2015
		// discuss at: http://phpjs.org/functions/is_numeric
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: David
		// +   improved by: taith
		// +   bugfixed by: Tim de Koning
		// +   bugfixed by: WebDevHobo (http://webdevhobo.blogspot.com/)
		// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
		// *     example 1: is_numeric(186.31);
		// *     returns 1: true
		// *     example 2: is_numeric('Kevin van Zonneveld');
		// *     returns 2: false
		// *     example 3: is_numeric('+186.31e2');
		// *     returns 3: true
		// *     example 4: is_numeric('');
		// *     returns 4: false
		// *     example 4: is_numeric([]);
		// *     returns 4: false
	return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}

function is_array(variable) {
	return Object.prototype.toString.call(variable) === '[object Array]' ? true : false;
}
function is_object(variable) {
	return Object.prototype.toString.call(variable) === '[object Object]' ? true : false;
}