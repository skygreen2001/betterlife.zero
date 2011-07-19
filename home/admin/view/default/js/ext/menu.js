    Ext.namespace("betterlife.admin");
    bb = betterlife.admin; 
    
    bb.Menu=Ext.emptyFn;
    
    bb.Menu.Loader=Ext.extend(Ext.ux.tree.XmlTreeLoader, 
    {
        processAttributes : function(attr){
            if(attr.first){ // is it an author node?

                // Set the node text that will show in the tree since our raw data does not include a text attribute:
                attr.text = attr.first + ' ' + attr.last;

                // Author icon, using the gender flag to choose a specific icon:
                attr.iconCls = 'author-' + attr.gender;

                // Override these values for our folder nodes because we are loading all data at once.  If we were
                // loading each node asynchronously (the default) we would not want to do this:
                attr.loaded = true;
                attr.expanded = true;
            }
            else if(attr.title){ // is it a book node?

                // Set the node text that will show in the tree since our raw data does not include a text attribute:
                attr.text = attr.title + ' (' + attr.published + ')';

                // Book icon:
                attr.iconCls = 'book';

                // Tell the tree this is a leaf node.  This could also be passed as an attribute in the original XML,
                // but this example demonstrates that you can control this even when you cannot dictate the format of
                // the incoming source XML:
                attr.leaf = true;
            }
        }
    }
);

