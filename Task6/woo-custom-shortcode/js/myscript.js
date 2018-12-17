$(document).ready(function(){
	var controller = {
		init: function()
		{
			controller.registerEvent();
		},
		registerEvent: function()
		{
			// process for button get code popup plugin
			$('#btn_popup').off('click').on('click',function(e){				
					e.preventDefault();
					var display_by = $('.display_by option:selected').val();
					var sort = $('#sorting_by option:selected').val();
					var display = $('#display_type option:selected').val();

					var list = [];
					var short_code = "";

					switch(display_by)
					{
						case "cate":
							var cate = $('#select_Category option:selected').val();
							//process if cate null
							if (cate == "")
							{							
								if (sort == "" && display == "")
								{
									short_code ='[featured_product][/featured_product]';

								}
								else if ( display != "" && sort == "")
								{
									short_code ='[featured_product display="' +display+'"][/featured_product]';
								}
								else if ( sort != "" && display == "")
								{
									short_code ='[featured_product sortingby="'+sort+'"][/featured_product]';
								}
								else
								{
									short_code ='[featured_product sortingby="'+sort+'" display="' +display+'"][/featured_product]';
								}								
							}
							else
							{
								if (sort == "" && display == "")
								{
									short_code ='[featured_product cate="'+cate+'"][/featured_product]';
								}
								else if ( display != "" && sort == "")
								{
									short_code ='[featured_product cate="'+cate+'" display="' +display+'"][/featured_product]';
								}
								else if ( sort != "" && display == "")
								{
									short_code ='[featured_product cate="'+cate+'" sortingby="'+sort+'"][/featured_product]';
								}
								else
								{
									short_code ='[featured_product cate="'+cate+'" sortingby="'+sort+'" display="' +display+'"][/featured_product]';
								}
								// short_code ='[featured_product cate="'+cate+'" sortingby="'+sort+'" display="' +display+'"]');
							}	
																		
						break;

						case "id":
							$('.ckb_checkListID').each(function() {
								if (this.checked) 
								{
									 list.push($(this).data("id"));
		       					}					
							});
							if (list.length > 0)
							{
								if (sort == "" && display == "")
								{
									short_code ='[featured_product id="'+list+'"][/featured_product]';
								}
								else if ( display != "" && sort == "")
								{
									short_code ='[featured_product id="'+list+'" display="' +display+'"][/featured_product]';
								}
								else if ( sort != "" && display == "")
								{
									short_code ='[featured_product id="'+list+'" sortingby="'+sort+'"][/featured_product]';
								}
								else
								{
									short_code ='[featured_product id="'+list+'" sortingby="'+sort+'" display="' +display+'"][/featured_product]';
								}
							}
							else
							{
								if (sort == "" && display == "")
								{
									short_code ='[featured_product][/featured_product]';
								}
								else if ( display != "" && sort == "")
								{
									short_code ='[featured_product display="' +display+'"][/featured_product]';
								}
								else if ( sort != "" && display == "")
								{
									short_code ='[featured_product sortingby="'+sort+'"][/featured_product]';
								}
								else
								{
									short_code ='[featured_product sortingby="'+sort+'" display="' +display+'"][/featured_product]';
								}
							}

							break;
							// 

						default:
							if (sort == "" && display == "")
							{
								short_code ='[featured_product][/featured_product]';
							}
							else if ( display != "" && sort == "")
							{
								short_code ='[featured_product display="' +display+'"][/featured_product]';
							}
							else if ( sort != "" && display == "")
							{
								short_code ='[featured_product sortingby="'+sort+'"][/featured_product]';
							}
							else
							{
								short_code ='[featured_product sortingby="'+sort+'" display="' +display+'"][/featured_product]';
							}
						break;
					}								
					getShortCode(short_code);
					
					
			});//end click
			// close popup and get shortcode
			function getShortCode(short_code)
			{
				//alert(short_code);
				parent.tinyMCE.activeEditor.setContent(parent.tinyMCE.activeEditor.getContent() + short_code);
				self.parent.tb_remove();
			}

			// process for choose select display by
			$('.display_by').change(function(){

				var display_by = $('.display_by option:selected').val();
				if (display_by == 'cate')
				{
					$('.cate_box').css({
						'display': 'block',
						'transition': '0.4s'
					});
					$('.ListID').css({
						'display': 'none',
						'transition': '0.4s'
					});
					$('.sorting_box').css({
						'display':'none',
						'transition':'0.3'
					});
					//$('.cate_box')
				}
				else if (display_by == 'id')
				{
					$('.ListID').css({
						'display': 'block',
						'trasition': '0.4s'
					});

					$('.cate_box').css({
						'display': 'none',
						'trasition': '0.4s'
					});
					$('.sorting_box').css({
						'display':'block',
						'transition':'0.3'
					});
				}
				else 
				{
					$('.cate_box').css({
						'display': 'none',
						'trasition': '0.4s'
					});
					$('.ListID').css({
						'display': 'none',
						'trasition': '0.4s'
					});
				}
			});//end change

			// $('')

		}
	}//end controller
	controller.init();
});


// append div

// modal 