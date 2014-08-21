Ext.namespace('Phlexible.frontend.accordion');

Phlexible.elements.ElementTabPanel.prototype.populateItems = Phlexible.elements.ElementTabPanel.prototype.populateItems.createSequence(function () {
    var index = false;

    for (var i = 0; i < this.items.length; i++) {
        if (this.items[i].xtype == 'elements-elementhistorygrid') {
            index = i;
            break;
        }
    }
    if (index === false) return;

    var item = {
        xtype: 'elements-elementpreviewpanel',
        element: this.element
    };

    this.items.splice(index, 0, item);
});

Phlexible.elements.TopToolbar.prototype.populateExtendedMenu =
    Phlexible.elements.TopToolbar.prototype.populateExtendedMenu.createSequence(function () {
        this.extendedMenuIndex.insert(0, 'preview_sep', '-');
        this.extendedMenuIndex.insert(0, 'preview', {
            // items[6]
            xtype: 'tbsplit',
            text: Phlexible.frontend.Strings.preview,
            iconCls: 'p-frontend-preview_page-icon',
            disabled: true,
            handler: function () {
                var src = this.element.data.urls.preview;
                window.open(src, 'latest_preview'); //, 'width=1000,height=700,scrollbars=yes');
            },
            scope: this,
            menu: [
                {
                    text: Phlexible.frontend.Strings.preview,
                    iconCls: 'p-frontend-preview_preview-icon',
                    handler: function () {
                        var src = this.element.data.urls.preview;
                        window.open(src, 'preview'); //, 'width=1000,height=700,scrollbars=yes');
                    },
                    scope: this
                },
                {
                    text: Phlexible.frontend.Strings.preview_online,
                    iconCls: 'p-frontend-preview_online-icon',
                    disabled: true,
                    handler: function () {
                        var src = this.element.data.urls.online;
                        window.open(src, 'preview_live'); //, 'width=1000,height=700,scrollbars=yes');
                    },
                    scope: this
                }
            ]
        });
    });

Phlexible.elements.TopToolbar.prototype.onLoadElement =
    Phlexible.elements.TopToolbar.prototype.onLoadElement.createSequence(function (element) {
        // enable preview button only for full elements
        var extendedItem = this.items.items[this.tbarIndex.indexOfKey('extended')];
        var previewItem = extendedItem.menu.items.items[this.extendedMenuIndex.indexOfKey('preview')];
        if (element.properties.et_type == Phlexible.elementtypes.TYPE_FULL) {
            previewItem.enable();
            if (element.properties.is_published) {
                previewItem.menu.items.items[1].enable();
            }
            else {
                previewItem.menu.items.items[1].disable();
            }
        }
        else {
            previewItem.disable();
        }
    });
