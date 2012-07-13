window.app.plugins.push({
	_app: null,
	_require: null,
	_client: null,
	init: function(app, require) {
        this._app     = app;
        this._require = require;
        this._determineClient();
        this._modifyView();
	},
	showClient: function() {
		
	},
	_modifyView: function() {
	    this._modifyCommentsView();
	    this._modifyTopicView();
	    this._modifyStropeToReceiveExtraData();
	    this._modifyTemplate();
	},
	_modifyCommentsView: function() {
		var CommentsView = this._require('../../views/channel/comments').CommentsView;
	    var createPost = CommentsView.prototype.createPost;
	    CommentsView.prototype.createPost = function (text) {
	        var post = createPost.call(this, text);
	        if (this.$('.client-interface') != undefined)
	          post.client = { interface: this.$('.client-interface').val(), };
	        return post;
	    };
	},
	_modifyTopicView: function() {
	    var PostsView = this._require('../../views/channel/posts').PostsView;
	    var createPost = PostsView.prototype.createPost;
	    PostsView.prototype.createPost = function (text) {
	        var post = createPost.call(this, text);
	        post.client = { interface: this.parent.$('.client-interface').val(), };
	        return post;
	    };
	},
	_modifyStropeToReceiveExtraData: function() {
		app.handler.connection.connection.buddycloud.addTag('client', 'interface');
	},
	_modifyTemplate: function() {
		var self            = this;
        var dynamictemplate = this._require('dynamictemplate').Template;
        var $               = this._require('dt-selector');
        var ready           = dynamictemplate.prototype.ready;

        dynamictemplate.prototype.ready = function(callback) {
        	var tpl = ready.call(this, callback);
        	if (this.xml != undefined) {
        		$(tpl).on('.controls', function(el) {
    				self._addDataField(el);
        		});
	        	$(tpl).on('.postmeta', function(el) {
                    if (typeof(el.builder.opts.view) != 'undefined'
                    	&& typeof(el.builder.opts.view.model) != 'undefined'
                    	&& el.builder.opts.view.model.attributes.client) {
                	    client = el.builder.opts.view.model.attributes.client.interface;
	        		    if (jQuery(el._jquery).find('.client-used').length == 0) {
	        			    el._jquery.append('<span class="client-used">Sent from ' + client + '</span>');
	        	        };   
        	       };
	        	});
	        	
	        	$(tpl).on('.content', function(el) {
	        		el._jquery.append('<style> .client-used { display: block;} </style>');
	        	});
        	};
        	return tpl;
        };
	},
	_determineClient: function() {
		userAgent = window.navigator.userAgent;
		this._client = "the web";
		if (userAgent.indexOf('Android') >= 0) {
			this._client = "Buddydroid";
		} else if (userAgent.indexOf('iPhone') >= 0) {
			this._client = "iBuddy";
		}
		console.debug('Detected client as "' + this._client + '"');
	},
	_addDataField: function(el) {
		if (el.parent._jquery.attr('class') != 'answer' 
			&& el.parent._jquery.attr('class') != 'newTopic'
	    ) {
        	return;
        }
		if (jQuery(el._jquery).find('.client-interface').length == 0) {
		    el._jquery.append('<input type="hidden" class="client-interface" value="' + this._client + '" />');
		}
	}
});