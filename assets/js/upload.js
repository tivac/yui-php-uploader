/*jshint browser:true, yui:true */
var Y = YUI({ combine : false, filter : "raw" });

Y.use(
    "array-extras",
    "parallel",
    "uploader-html5",
    "uploader-queue",
    "node",
    "event",
    "template",
    function(Y) {
        "use strict";
        
        var engine = new Y.Template(),
            pending, completed, uploader;
        
        uploader = new Y.UploaderHTML5({
            multipleFiles : true,
            uploadURL     : "http://tivac.com/upload/upload.php",
            simLimit      : 5,
            fileFilters   : [ "image/*" ],
            dragAndDropArea : ".drop"
        });
        
        uploader.render(".hd");
        
        uploader.after({
            fileselect : function(e) {
                console.log(e.type, e);
                
                var parallel = new Y.Parallel();
                
                if(!pending) {
                    pending = engine.compile(Y.one("[data-template='pending']").get("text"));
                }
                
                Y.Array.each(e.fileList, function(file) {
                    var reader = new FileReader();
                    
                    reader.onload = parallel.add(function(e) {
                        file.set("dataurl", e.target.result);
                    });
                    
                    reader.readAsDataURL(file.get("file"));
                });
                
                parallel.done(function() {
                    Y.one(".images").append(
                        Y.Array.reduce(e.fileList, "", function(prev, curr) {
                            return prev + pending(
                                curr.getAttrs([ "name", "size", "dataurl" ])
                            );
                        })
                    );
                });
                
                Y.one("[data-upload]").removeAttribute("disabled");
            },
            
            uploadprogress : function(e) {
                var idx = this.get("fileList").indexOf(e.file);
                
                console.log(e.type, e, idx);
            },
            
            totaluploadprogress : function(e) {
                console.log(e.type, e);
            },
            
            uploadstart : function(e) {
                console.log(e.type, e);
                
                Y.one("[data-upload]").setAttribute("disabled");
            },
            
            uploadcomplete : function(e) {
                console.log(e.type, e);
            },
            
            alluploadscomplete : function(e) {
                console.log(e.type, e);
            }
        }, null, uploader);
        
        
        Y.one("[data-upload]").on("click", function(e) {
            e.preventDefault();
            
            uploader.uploadAll();
        });
    }
);
