var Subjects = {
	/*** ### SubjectSubgroup ### ***/
		expandSubject: function(post) {
			var selected = Subjects.select_subjectExpanded();
			if(selected != undefined)
			{
				var IdDOM = selected.split("_"),
				c_post = IdDOM[2] + "_" + IdDOM[3] + "_" + IdDOM[4];
			}
			if(selected != undefined && c_post == post) Subjects.turnSubject();
			else
			{
				Subjects.turnSubject();
				var sps = post.split("_"); //{subject_group_material}
				if(sps[2] != 0 )
				{
					ajax.post_bs("do/subjects/loadMaterials.php","idMaterial=" + sps[2],
		            function(data){ /**ge("loadNotes_" + idLoad).addClass('activeLoad');**/ },
					function(data){
						if(data[0] == 'unkmnown' && data[1] == null) {}
						else if(data[0] == 'loadsections' && data[1] == null) {}
						else if(data[0] == 'loadsections' && data[1] != null) 
						{
							Subjects.insertSubjectMaterial(post, Object.keys(data[1]).length > 0 ? data[1] : null);
						}
					},
					function(XMLHttpRequest, textStatus, errorThrown){ 
						// console.log("error from ajax: " + XMLHttpRequest.responseText);
						var errorMessage = "";
					}
					);
				}
				else { Subjects.insertSubjectMaterial(post, null); }
			}
		},
		turnSubject: function() {
			/** turn of all expandet **/
			var subject_opened = $('tr[id^=select_subject_]');
			subject_opened.remove();
		},
		insertSubjectMaterial: function(post, data){
			/** insert new expand block **/
	        var prepareHTML = "", ins = "", note = "";
	        var subject = ge("study_" + post);
	        var subject_color =subject.find('div#procent').css('background-color');

			ins += "<tr id='select_subject_" + post + "'>\
						<td colspan='3' class='droped' style='border-left:5px solid " + subject_color + "'> <ul>";
				if(data != null)
				{
					for(var i in data)
					{
				        section = {
							idSection: data[i]["idSection"],
							idSubject: data[i]["idSubject"],
							name: data[i]["name"],
							number: data[i]["number"]
						};
						ins += 	"<li id='section_" + post + "_" + section.idSection + "'>\
									<span onclick='Subjects.expandSection(\"" + post + "_" + section.idSection + "\");'>Раздел " + section.number + ": " + section.name + "</span>\
									<ul id='paragraphs_" + post + "_" + section.idSection + "'>\
									</ul>\
								</li>";
					}
				} else { ins += "<li><span class='alert'>Отсутствуют разделы в предмете</span></li>"; }
			ins += 	"</ul> </td> </tr>";
			subject.after(ins).show();
		},
		select_subjectExpanded: function(){
			var subject_opened = $('tr[id^=select_subject_]');
			return subject_opened.attr("id");
		},
	/*** ### Section ### ***/
		expandSection: function(post) {
			var selected = Subjects.opened_sectionExpanded();
			if(selected != undefined)
			{
				var IdDOM = selected.split("_"),
				c_post = IdDOM[1] + "_" + IdDOM[2] + "_" + IdDOM[3] + "_" + IdDOM[4];
			}
			if(selected != undefined && c_post == post) Subjects.turnSection();
			else
			{
				Subjects.turnSection();
				var sps = post.split("_"); //{subject_group_material_section}
				ajax.post_bs("do/subjects/loadMaterials.php","idMaterial=" + sps[2] + "&idSection=" + sps[3],
		        function(data){ /**ge("loadNotes_" + idLoad).addClass('activeLoad');**/ },
				function(data){
					if(data[0] == 'unkmnown' && data[1] == null) {}
					else if(data[0] == 'loadparagraphs' && data[1] == null) {}
					else if(data[0] == 'loadparagraphs' && data[1] != null) 
					{
						Subjects.insertSectionParagraphs(post, Object.keys(data[1]).length > 0 ? data[1] : null);
					}
				},
				function(XMLHttpRequest, textStatus, errorThrown){ 
					// console.log("error from ajax: " + XMLHttpRequest.responseText);
					var errorMessage = "";
				}
				);		
			}
		},
		turnSection: function() {
			// ge('section_' + post).find('ul').html('');
			var section_opened = $('tr td ul li ul').html('');
		},
		insertSectionParagraphs: function(post, data) {
			/** insert new expand block **/
	        var prepareHTML = "", ins = "", note = "",
	        	section = ge("section_" + post),
	        	paragraphs = section.find('ul'),
	        	schoolId = ($('li[id^=school_]').attr("id").split("_"))[1];

			if(data != null)
			{
				for(var i in data)
				{
			        paragraph = {
						countpart: data[i]["countpart"],
						countquestion: data[i]["countquestion"],
						datecreate: data[i]["datecreate"],
						dateupdate: data[i]["dateupdate"],
						idMaterial: data[i]["idMaterial"],
						idParagraph: data[i]["idParagraph"],
						idSection: data[i]["idSection"],
						isTestReady: data[i]["isTestReady"],
						name: data[i]["name"],
						notstudy: data[i]["notstudy"],
						number: data[i]["number"],
						state: data[i]["state"]
					};
					ins += 	"<li>\
								<a href='paragraph.php?school=" + schoolId + 
									"&paragraph=" + paragraph.idParagraph + "'>§ " + 
											paragraph.number + ". " + paragraph.name + 
								"</a>\
							</li>";
				}
			}
			else { ins += 	"<li><span class='alert'>Раздел пуст</span></li>"; }

			paragraphs.html(ins).show();
			return true;
		},
		opened_sectionExpanded: function(){
			var section_opened = $('tr td ul li ul li');
			return (section_opened.length > 0) ? section_opened.parent().attr("id") : undefined;
		},
};