function ConfigurationManager() {
    var self = this ;
    this.init = function () {
        var $container_config_name = $('div#form_config_names');
        var $container_config_type = $('div#form_config_types');

        $('#add_configuration_row').click(function(e) {
            
            self.addConfigRow($container_config_name, $container_config_type);

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
		
		var index = $('div#form_config_names').find('.form-group').length ;
		if(index == 0) {
			self.addConfigRow($container_config_name, $container_config_type, "", "");
		} else {
			var name_values = [] ;
			var type_values = [] ;
			var temp = "" ;
			for(var i = 0 ; i < index ; i++) {
				temp = $('div#form_config_names').find('.form-group').eq(i).find('input').eq(0).val() ;
				name_values.push(temp) ;
				
				temp = $('div#form_config_types').find('.form-group').eq(i).find('select').eq(0).val() ;
				type_values.push(temp) ;	
			}
			
			$('div#form_config_names').html("");
        	$('div#form_config_types').html("");
			
			for(var i = 0 ; i < name_values.length ; i++) {
				self.addConfigRow($container_config_name, $container_config_type, name_values[i], type_values[i]);	
			}
		}
    }, 
    this.addConfigRow = function ($container_config_name, $container_config_type, $config_name_value, $config_type_value) {
        var index = $('div#list_config_rows').find('.form-group').length / 2;

        var template = $container_config_name.attr('data-prototype')
        .replace(/__name__label__/g, 'Configuration n°' + (index+1))
        .replace(/__name__/g,        index) ;

        var $prototype_1 = $(template);
		
		$prototype_1.find('input').eq(0).val($config_name_value) ;

        $('#list_config_rows').append($prototype_1);

        //###########################################
        var template = $container_config_type.attr('data-prototype')
        .replace(/__name__label__/g, 'Configuration type n°' + (index+1))
        .replace(/__name__/g,        index)  ;

        var $prototype_2 = $(template);
		
		$prototype_2.find('select').eq(0).val($config_type_value) ;

        $('#list_config_rows').append($prototype_2);    

        self.addDeleteRowLink($prototype_1, $prototype_2);
    },
    this.addDeleteRowLink = function ($prototype_1, $prototype_2) {
        var $deleteLink = $('<a href="#" class="btn btn-danger" style="margin-top:15px;"><i class="glyphicon glyphicon-trash"></i> Delete</a>');

        $prototype_2.append($deleteLink);

        $deleteLink.click(function(e) {
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            $prototype_1.remove();
            $prototype_2.remove();
            $prototype_2.prev().remove();
            return false;
        });
    }
}

function ControllerManager() {
    var self = this ;
    this.init = function () {
        var $container_controller_name = $('div#form_controller_names');
        var $container_controller_config = $('div#form_controller_configs');

        $('#add_controller_row').click(function(e) {
            
            self.addControllerRow($container_controller_name, $container_controller_config);

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });

        //self.addControllerRow($container_controller_name, $container_controller_config);
		var index = $('div#form_controller_names').find('.form-group').length ;
		if(index == 0) {
			self.addControllerRow($container_controller_name, $container_controller_config, "", "");
		} else {
			var name_values = [] ;
			var config_values = [] ;
			var temp = "" ;
			for(var i = 0 ; i < index ; i++) {
				temp = $('div#form_controller_names').find('.form-group').eq(i).find('input').eq(0).val() ;
				name_values.push(temp) ;
				
				temp = $('div#form_controller_configs').find('.form-group').eq(i).find('select').eq(0).val() ;
				config_values.push(temp) ;	
			}
			
			$('div#form_controller_names').html("");
        	$('div#form_controller_configs').html("");
			
			for(var i = 0 ; i < name_values.length ; i++) {
				self.addControllerRow($container_controller_name, $container_controller_config, name_values[i], config_values[i]);	
			}
		}
    }, 
    this.addControllerRow = function ($container_controller_name, $container_controller_config, $controller_name_value, $controller_config_value) {
        var index = $('div#list_controller_rows').find('.form-group').length / 2;

        var template = $container_controller_name.attr('data-prototype')
        .replace(/__name__label__/g, 'Controller n°' + (index+1))
        .replace(/__name__/g,        index) ;

        var $prototype_1 = $(template);
		
		$prototype_1.find('input').eq(0).val($controller_name_value) ;

        $('#list_controller_rows').append($prototype_1);
        //###########################################
        var template = $container_controller_config.attr('data-prototype')
        .replace(/__name__label__/g, 'Controller configuration n°' + (index+1))
        .replace(/__name__/g,        index)  ;

        var $prototype_2 = $(template);
		
		$prototype_2.find('select').eq(0).val($controller_config_value) ;

        $('#list_controller_rows').append($prototype_2);    

        self.addDeleteRowLink($prototype_1, $prototype_2);
    },
    this.addDeleteRowLink = function ($prototype_1, $prototype_2) {
        var $deleteLink = $('<a href="#" class="btn btn-danger" style="margin-top:10px;"><i class="glyphicon glyphicon-trash"></i>  Delete</a>');

        $prototype_2.append($deleteLink);

        $deleteLink.click(function(e) {
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            $prototype_1.remove();
            $prototype_2.remove();
            $prototype_2.prev().remove();
            return false;
        });
    }
}

var ConfigurationManagerClass ;
var ControllerManagerClass ;

(function($) { 
    $(document).ready(function() {
        
        var ConfigurationManagerClass = new ConfigurationManager() ;
        ConfigurationManagerClass.init() ;

        var ControllerManagerClass = new ControllerManager() ;
        ControllerManagerClass.init() ;
    });
})(jQuery)