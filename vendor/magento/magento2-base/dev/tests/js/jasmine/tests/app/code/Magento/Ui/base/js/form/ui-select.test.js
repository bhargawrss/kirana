/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/*eslint max-nested-callbacks: 0*/
define([
    'underscore',
    'uiRegistry',
    'squire',
    'ko'
], function (_, registry, Squire, ko) {
    'use strict';

    describe('Magento_Ui/js/form/element/ui-select', function () {
        var injector = new Squire(),
            mocks = {
                'Magento_Ui/js/lib/registry/registry': {
                    /** Method stub. */
                    get: function () {
                        return {
                            get: jasmine.createSpy(),
                            set: jasmine.createSpy()
                        };
                    },
                    create: jasmine.createSpy(),
                    set: jasmine.createSpy(),
                    async: jasmine.createSpy()
                },
                '/mage/utils/wrapper': jasmine.createSpy()
            },
            obj,
            dataScope = 'abstract';

        beforeEach(function (done) {
            injector.mock(mocks);
            injector.require([
                'Magento_Ui/js/form/element/ui-select',
                'knockoutjs/knockout-es5'
            ], function (Constr) {
                obj = new Constr({
                    provider: 'provName',
                    name: '',
                    index: '',
                    dataScope: dataScope,
                    options: {
                        showsTime: true
                    }
                });

                obj.value = ko.observableArray([]);
                obj.cacheOptions.plain = [];

                done();
            });
        });

        describe('"initialize" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('initialize')).toBeDefined();
            });
            it('Check method type', function () {
                var type = typeof obj.initialize;

                expect(type).toEqual('function');
            });
        });

        describe('"initObservable" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('initObservable')).toBeDefined();
            });
            it('Check method type', function () {
                var type = typeof obj.initialize;

                expect(type).toEqual('function');
            });
        });

        describe('"outerClick" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('outerClick')).toBeDefined();
            });
            it('Check method type', function () {
                var type = typeof obj.outerClick;

                expect(type).toEqual('function');
            });
            it('Variable "this.listVisible" must be false ', function () {
                obj.listVisible(true);
                obj.outerClick();
                expect(obj.listVisible()).toEqual(false);
            });
        });

        describe('"hasData" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('hasData')).toBeDefined();
            });
            it('Check method type', function () {
                var type = typeof obj.hasData;

                expect(type).toEqual('function');
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.hasData();

                expect(type).toEqual('boolean');
            });
            it('Must be false if selected array length is 0', function () {
                obj.value([]);
                expect(obj.hasData()).toEqual(false);
            });
            it('Must be true if selected array length is 0', function () {
                obj.value(['magento']);
                expect(obj.hasData()).toEqual(true);
            });
        });

        describe('"removeSelected" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('removeSelected')).toBeDefined();
            });
            it('Check method type', function () {
                var type = typeof obj.removeSelected;

                expect(type).toEqual('function');
            });
            it('Must remove data from selected array', function () {
                obj.value(['magento', 'magento2']);
                obj.removeSelected('magento');
                expect(obj.value()).toEqual(['magento2']);
            });
        });

        describe('"isTabKey" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('isTabKey')).toBeDefined();
            });
            it('Check method type', function () {
                var type = typeof obj.isTabKey;

                expect(type).toEqual('function');
            });
            it('Check returned value type if method called without arguments', function () {
                var event = {
                        keyCode: 9
                    },
                    type = typeof obj.isTabKey(event);

                expect(type).toEqual('boolean');
            });
            it('Must return false if pressed not tab key', function () {
                var event = {
                    keyCode: 9
                };

                expect(obj.isTabKey(event)).toEqual(true);
            });
            it('Must return true if pressed tab key', function () {
                var event = {
                    keyCode: 33
                };

                expect(obj.isTabKey(event)).toEqual(false);
            });
        });

        describe('"cleanHoveredElement" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('cleanHoveredElement')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.cleanHoveredElement;

                expect(type).toEqual('function');
            });
            it('Check returned value if method called without arguments', function () {
                expect(obj.cleanHoveredElement()).toBeDefined();
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.cleanHoveredElement();

                expect(type).toEqual('object');
            });
        });
        describe('"isSelected" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('isSelected')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.isSelected;

                expect(type).toEqual('function');
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.isSelected();

                expect(type).toEqual('boolean');
            });
            it('Must return true if array "selected" has value', function () {
                obj.value(['magento']);
                expect(obj.isSelected('magento')).toEqual(true);
            });
            it('Must return false if array "selected" has not value', function () {
                obj.value(['magento']);
                expect(obj.isSelected('magentoTwo')).toEqual(false);
            });
        });
        describe('"isHovered" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('isHovered')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.isHovered;

                expect(type).toEqual('function');
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.isHovered();

                expect(type).toEqual('boolean');
            });
        });
        describe('"toggleListVisible" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('toggleListVisible')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.toggleListVisible;

                expect(type).toEqual('function');
            });
            it('Check returned value if method called without arguments', function () {
                expect(obj.cleanHoveredElement()).toBeDefined();
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.toggleListVisible();

                expect(type).toEqual('object');
            });
            it('Must be false if "listVisible" is true', function () {
                obj.listVisible(true);
                obj.toggleListVisible();
                expect(obj.listVisible()).toEqual(false);
            });
            it('Must be true if "listVisible" is false', function () {
                obj.listVisible(false);
                obj.toggleListVisible();
                expect(obj.listVisible()).toEqual(true);
            });
        });
        describe('"toggleOptionSelected" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('toggleOptionSelected')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.toggleOptionSelected;

                expect(type).toEqual('function');
            });
            it('Check returned value if method called without arguments', function () {
                var data = {
                    value: 'label'
                };

                expect(obj.toggleOptionSelected(data)).toBeDefined();
            });
            it('Check returned value type if method called without arguments', function () {
                var data = {
                    value: 'label'
                },
                    type = typeof obj.toggleOptionSelected(data);

                expect(type).toEqual('object');
            });
            it('Transmitted value must be in "selected" array if "selected" array has not this value', function () {
                var data = {
                    value: 'label'
                };

                obj.value(['magento']);
                obj.toggleOptionSelected(data);
                expect(obj.value()[1]).toEqual(data.value);
            });
            it('Transmitted value must be removed in "selected" array if "selected" array has this value', function () {
                var data = {
                    value: 'label'
                };

                obj.value(['label']);
                obj.toggleOptionSelected(data);
                expect(obj.value()).toEqual([]);
            });
        });
        describe('"onFocusIn" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('onFocusIn')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.onFocusIn;

                expect(type).toEqual('function');
            });
            it('Observe variable "multiselectFocus" must be true', function () {
                obj.onFocusIn({}, {});
                expect(obj.multiselectFocus()).toEqual(true);
            });
        });
        describe('"onFocusOut" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('onFocusOut')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.onFocusOut;

                expect(type).toEqual('function');
            });
            it('Observe variable "multiselectFocus" must be false', function () {
                obj.onFocusOut();
                expect(obj.multiselectFocus()).toEqual(false);
            });
        });
        describe('"enterKeyHandler" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('enterKeyHandler')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.enterKeyHandler;

                expect(type).toEqual('function');
            });
            it('Observe variable "listVisible" must be true', function () {
                obj.listVisible(false);
                obj.enterKeyHandler();
                expect(obj.listVisible()).toEqual(true);
            });
        });
        describe('"escapeKeyHandler" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('escapeKeyHandler')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.escapeKeyHandler;

                expect(type).toEqual('function');
            });
            it('if list visible is true, method "setListVisible" must be called with argument "false"', function () {
                var setListVisibleCache = obj.setListVisible;

                obj.listVisible(true);
                obj.setListVisible = jasmine.createSpy();
                obj.escapeKeyHandler();
                expect(obj.setListVisible).toHaveBeenCalledWith(false);
                obj.setListVisible = setListVisibleCache;
            });
        });
        describe('"pageDownKeyHandler" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('pageDownKeyHandler')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.pageDownKeyHandler;

                expect(type).toEqual('function');
            });
        });
        describe('"pageUpKeyHandler" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('pageUpKeyHandler')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.pageUpKeyHandler;

                expect(type).toEqual('function');
            });
        });
        describe('"keydownSwitcher" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('keydownSwitcher')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.keydownSwitcher;

                expect(type).toEqual('function');
            });
            it('If press enter key must be called "enterKeyHandler" method', function () {
                obj.enterKeyHandler = jasmine.createSpy();
                obj.keydownSwitcher({}, {
                    keyCode: 13
                });
                expect(obj.enterKeyHandler).toHaveBeenCalled();
            });
            it('If press escape key must be called "escapeKeyHandler" method', function () {
                obj.escapeKeyHandler = jasmine.createSpy();
                obj.keydownSwitcher({}, {
                    keyCode: 27
                });
                expect(obj.escapeKeyHandler).toHaveBeenCalled();
            });
            it('If press space key must be called "enterKeyHandler" method', function () {
                obj.enterKeyHandler = jasmine.createSpy();
                obj.keydownSwitcher({}, {
                    keyCode: 32
                });
                expect(obj.enterKeyHandler).toHaveBeenCalled();
            });
            it('If press pageup key must be called "pageUpKeyHandler" method', function () {
                obj.pageUpKeyHandler = jasmine.createSpy();
                obj.keydownSwitcher({}, {
                    keyCode: 38
                });
                expect(obj.pageUpKeyHandler).toHaveBeenCalled();
            });
            it('If press pagedown key must be called "pageDownKeyHandler" method', function () {
                obj.pageDownKeyHandler = jasmine.createSpy();
                obj.keydownSwitcher({}, {
                    keyCode: 40
                });
                expect(obj.pageDownKeyHandler).toHaveBeenCalled();
            });
            it('If object have not transmitted property must returned true', function () {
                expect(obj.keydownSwitcher({}, {
                    keyCode: 88
                })).toEqual(true);
            });
        });
        describe('"setCaption" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('setCaption')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.setCaption;

                expect(type).toEqual('function');
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.setCaption();

                expect(type).toEqual('string');
            });
            it('Check returned value if selected array length more then 1', function () {
                obj.value(['one', 'two']);

                expect(obj.setCaption()).toEqual('2 ' + obj.selectedPlaceholders.lotPlaceholders);
            });
            it('Check returned value if selected array length is 0', function () {
                obj.value([]);

                expect(obj.setCaption()).toEqual(obj.selectedPlaceholders.defaultPlaceholder);
            });
        });
        describe('"keyDownHandlers" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('keyDownHandlers')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.keyDownHandlers;

                expect(type).toEqual('function');
            });
        });
        describe('"setListVisible" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('setListVisible')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.setListVisible;

                expect(type).toEqual('function');
            });
            it('Check "this.listVisible" if transmitted argument is false', function () {
                obj.listVisible(true);
                obj.setListVisible(false);
                expect(obj.listVisible()).toEqual(false);
            });
            it('Check "this.listVisible" if transmitted argument is true', function () {
                obj.listVisible(false);
                obj.setListVisible(true);
                expect(obj.listVisible()).toEqual(true);
            });
        });
        describe('"getSelected" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('getSelected')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.getSelected;

                expect(type).toEqual('function');
            });
            it('Check returned value if selected', function () {
                obj.cacheOptions.plain = [{
                    value: 'magento'
                }, {
                    value: 'magento2'
                }];
                obj.value(['magento', 'magento2']);

                expect(obj.getSelected()).toEqual([{
                    value: 'magento'
                }, {
                    value: 'magento2'
                }]);
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.getSelected();

                expect(type).toEqual('object');
            });
        });
        describe('"getPreview" method', function () {
            it('Check for defined ', function () {
                expect(obj.hasOwnProperty('getPreview')).toBeDefined();
            });
            it('Check answer type', function () {
                var type = typeof obj.getPreview;

                expect(type).toEqual('function');
            });
            it('Check returned value type if method called without arguments', function () {
                var type = typeof obj.getPreview();

                expect(type).toEqual('string');
            });
        });
    });
});
