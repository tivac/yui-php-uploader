<!DOCTYPE html>
<html>
<head>
	<title>Image Uploader</title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.1.0/pure-min.css">
    <link rel="stylesheet" href="/upload/assets/css/icons.css">
    <link rel="stylesheet" href="/upload/assets/css/upload.css">
</head>
<body>
    <div class="content">
        <h1>Upload Some Images</h1>
        
        <div class="hd"></div>
        
        <div class="bd">
            <div class="pure-g">
                <div class="pure-u-1-5 drop">
                    Files go here
                </div>
                
                <div class="pure-u-4-5">
                    <ol class="images"></ol>
                </div>
            </div>
        </div>
        
        <div class="ft">
            <button class="pure-button pure-button-primary" disabled data-upload>
                <i class="icon-file-upload"></i>
                Upload
            </button>
        </div>
    </div>
    
    <script type="text/template" data-template="pending">
        <li class="pending pure-g">
            <div class="preview pure-u">
                <img src="<%== this.dataurl %>" width="50" height="50" alt="<%= this.name %>" />
            </div>
            <div class="meta pure-u">
                <%= this.name %> (<%= this.size %>)
            </div>
        </li>
    </script>
    
    <script type="text/template" data-template="uploaded">
        <li class="uploaded">
            <a><img alt="<%= this.name %>"/></a>
            <form class="pure-form pure-form-aligned">
                <fieldset>
                    <div class="pure-control-group">
                        <label for="<%= this.hash %>-url">Image URL</label>
                        <input id="<%= this.hash %>-url" value="<%= this.url %>">
                    </div>
                    <div class="pure-control-group">
                        <label for="<%= this.hash %>-thumb">Thumb Link</label>
                        <input id="<%= this.hash %>-thumb" value="<%= this.thumb %>">
                    </div>
                    <div class="pure-control-group">
                        <label for="<%= this.hash %>-forum-image">Forum Image</label>
                        <input id="<%= this.hash %>-forum-image" value="<%= this.forum.image %>">
                    </div>
                    <div class="pure-control-group">
                        <label for="<%= this.hash %>-forum-thumb">Forum Thumb</label>
                        <input id="<%= this.hash %>-forum-thumb" value="<%= this.forum.thumb %>">
                    </div>
                </fieldset>
            </form>
        </li>
    </script>
    <script type="text/javascript" src="http://yui.yahooapis.com/combo?3.10.2/build/yui/yui-min.js"></script>
    <script type="text/javascript" src="/upload/assets/js/upload.js"></script>
</body>
</html>
