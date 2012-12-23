/* ===================================================
 * bootstrap-tagmanager.js v2.2
 * http://welldonethings.com/tags/manager
 * ===================================================
 * Copyright 2012 Max Favilli
 *
 * Licensed under the Mozilla Public License, Version 2.0 You may not use this work except in compliance with the License.
 *
 * http://www.mozilla.org/MPL/2.0/
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

"use strict";

(function (jQuery) {
  if (typeof console === "undefined" || typeof console.log === "undefined") {
    console = {};
    console.log = function () { };
  }
  
  var makeTag = function(name) {
      var elm = $('<li class="tag alert alert-info" ><button type="button" class="close" >&times;</button><span></span></li>');
      elm.find('span').text(name);
      return elm
  }
  
  var buildAutocomplete = function(query,result) {
      var elm = $('<ul class="dropdown-menu"></ul>');
      var length = query.length;
      var first = true;
      result.forEach(function(val) {
          var html = '<a href="#" rel="'+val+'" class="suggest"><b>'+val.substr(0,length)+'</b>'+val.substr(length)+'</a>';
          var listElm = $('<li />').html(html);
          if (first)
              listElm.addClass('active');
          first = false;
          
          elm.append(listElm);
      });
      
      
      return elm;
  };
  

  jQuery.fn.autocomplete = function (opts) {
      
      $(this).each(function() {
            var inputElm = $(this);
            var tagContainer = $('<ul class="tags">');
            var newTagElm = $('<li class="new"><input type="text" /></li>');
            
            inputElm.hide();
            inputElm.before(tagContainer);
            var tags = [];
            var val = inputElm.val();
            if (val && val.length > 0) {
                tags = val.split(',');
            }
            
            var markIx = -1;
            var autocomplete = null;
            var newTagInput = newTagElm.find('input');
            
            
            var syncToInput = function() {
                var val = tags.join(',');
                inputElm.val(val);
            };
            
            var repaint = function() {
                markIx = -1;
                tagContainer.children().detach();
                tags.forEach(function(tag) {
                    tagContainer.append(makeTag(tag));
                 });

                 tagContainer.append(newTagElm);
                 syncToInput();
                 newTagInput.focus();
            };
            
            var addTag = function(val) {
                if (tags.indexOf(val) === -1) {
                    tagContainer
                        .append(makeTag(val))
                        .append(newTagElm)
                    tags.push(val);
                    newTagInput.focus();
                    syncToInput();
                }
                newTagInput.val('');
            }
            
            
            
            repaint();
            
            
            tagContainer.find('.close').live('click',function(evt) {
                evt.stopPropagation();
                evt.preventDefault();
            });
            
            
            
            tagContainer.click(function(evt) {
                var target = $(evt.target);
                if (target.is('.close')) {
                    var tag = target.closest('.tag');
                    var ix = tagContainer.find('.tag').index(tag);
                    tags.splice(ix,1);
                    repaint();
                }
                
                if (target.closest('.suggest').length > 0)
                    target = target.closest('.suggest');
                
                if (target.is('.suggest')) {
                    var val = target.attr('rel');
                    addTag(val);
                    autocomplete.remove();
                    autocomplete = null;
                }
                
                
                newTagInput.focus();
            });
            
            newTagInput.bind('keypress',function(evt) {
                var val = newTagInput.val();
                
                switch(evt.keyCode) {
                    case 13: //Enter
                        evt.preventDefault();
                        
                        if (autocomplete) {
                            autocomplete.find('.active').click();
                            return;
                        }
                        
                        
                        if (val) {
                            addTag(val);
                        }
                        return;
                }
                
                tagContainer.children('.active').removeClass('active');
                markIx = -1;
                
            });
            
            newTagInput.bind('keydown',function(evt) {
                var val = newTagInput.val();
                
                if (autocomplete) {
                    var active = autocomplete.find('.active');
                    switch(evt.keyCode) {
                        case 38://up
                            if (active.prev().length > 0) {
                                active.removeClass('active');
                                active.prev().addClass('active');
                            }
                            return;
                        case 40://down
                            if (active.next().length > 0) {
                                active.removeClass('active');
                                active.next().addClass('active');
                            }
                            return;
                        case 13:
                            autocomplete.find('.active > a').click();
                            return;
                    }
                    
                    autocomplete.remove();
                    autocomplete = null;
                }
                
                
                if (tags.length > 0 
                        && evt.keyCode === 8 
                        && newTagInput.val() === '') {
                    
                    if (markIx > -1) {
                        tags.splice(markIx,1);
                        repaint();
                    } else {
                        markIx = tags.length-1;
                        tagContainer.children('.tag').eq(markIx).addClass('active');
                    }
                }
                
                
                
                if (opts.source && val.length > 1) {
                    $.getJSON(opts.source+'&term='+val,function(result) {
                        if (result.length > 0) {
                            autocomplete = buildAutocomplete(val,result)
                            newTagInput.after(autocomplete);
                        }
                        
                    });
                }
            });
            
      });
  }
})(jQuery);