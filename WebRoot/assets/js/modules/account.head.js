define(function(require,exports,module){
	var $ = require('jquery'); 
	var _ = require('underscore');
	var Backbone = require('backbone');
	var Mustache = require('mustache');	

	exports.Models={};
	exports.Views={};
	exports.Collections={};

	exports.Models.Account = Backbone.Model.extend({
		initialize:function(){
		
		}
	});
	exports.Collections.AccountList=Backbone.Collection.extend({
		model:exports.Models.Account,
		url:'/Auth/getlogineduser',
		account_data:$('div.settings-container').attr('data-account'),
		initialize:function(){},
		fetch_data:function(options){
			this.account_data=JSON.parse(this.account_data);
			if(this.account_data){
				this.add(this.account_data);
				this.max_model=this.max(function(model){return model.get('email').length});
				this.trigger('reset');
			}
			if(this.length>0&&options['success'])
				options['success'](); 
			if(this.length<1&&options['error'])
				options['error']();
		}
	});
	
	var AccountDropdown = Backbone.View.extend({
		el: '.settings-container',
		template_origin: $('div.settings-container').html(),
		
		events: {
			"click .settings-container .account_drop":"account_return_enter"
		},	
		initialize:function(){
			if(!this.template_origin)
				return;
			//this.account_list = this.account_list || new exports.Views.AccountList();
			//console.log(this.template_origin);
		},
		account_return_enter:function(){
			$('#account-dropdown').show();
		}
	});

	var account_dropdown = new AccountDropdown(); 
});
