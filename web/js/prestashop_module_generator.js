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

        self.addConfigRow($container_config_name, $container_config_type);
    }, 
    this.addConfigRow = function ($container_config_name, $container_config_type) {
        var index = $('div#list_config_rows').find('.form-group').length / 2;

        var template = $container_config_name.attr('data-prototype')
        .replace(/__name__label__/g, 'Configuration n°' + (index+1))
        .replace(/__name__/g,        index) ;

        var $prototype_1 = $(template);

        $('#list_config_rows').append($prototype_1);

        //###########################################
        var template = $container_config_type.attr('data-prototype')
        .replace(/__name__label__/g, 'Configuration type n°' + (index+1))
        .replace(/__name__/g,        index)  ;

        var $prototype_2 = $(template);

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

        self.addControllerRow($container_controller_name, $container_controller_config);
    }, 
    this.addControllerRow = function ($container_controller_name, $container_controller_config) {
        var index = $('div#list_controller_rows').find('.form-group').length / 2;

        var template = $container_controller_name.attr('data-prototype')
        .replace(/__name__label__/g, 'Controller n°' + (index+1))
        .replace(/__name__/g,        index) ;

        var $prototype_1 = $(template);

        $('#list_controller_rows').append($prototype_1);
        //###########################################
        var template = $container_controller_config.attr('data-prototype')
        .replace(/__name__label__/g, 'Controller configuration n°' + (index+1))
        .replace(/__name__/g,        index)  ;

        var $prototype_2 = $(template);

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