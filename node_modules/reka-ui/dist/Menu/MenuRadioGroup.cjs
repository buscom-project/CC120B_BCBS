'use strict';

const vue = require('vue');
const core = require('@vueuse/core');
const Menu_MenuGroup = require('./MenuGroup.cjs');
const shared_createContext = require('../shared/createContext.cjs');

const [injectMenuRadioGroupContext, provideMenuRadioGroupContext] = shared_createContext.createContext("MenuRadioGroup");
const _sfc_main = /* @__PURE__ */ vue.defineComponent({
  __name: "MenuRadioGroup",
  props: {
    modelValue: { default: "" },
    asChild: { type: Boolean },
    as: {}
  },
  emits: ["update:modelValue"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emits = __emit;
    const modelValue = core.useVModel(props, "modelValue", emits);
    provideMenuRadioGroupContext({
      modelValue,
      onValueChange: (payload) => {
        modelValue.value = payload;
      }
    });
    return (_ctx, _cache) => {
      return vue.openBlock(), vue.createBlock(Menu_MenuGroup._sfc_main, vue.normalizeProps(vue.guardReactiveProps(props)), {
        default: vue.withCtx(() => [
          vue.renderSlot(_ctx.$slots, "default", { modelValue: vue.unref(modelValue) })
        ]),
        _: 3
      }, 16);
    };
  }
});

exports._sfc_main = _sfc_main;
exports.injectMenuRadioGroupContext = injectMenuRadioGroupContext;
//# sourceMappingURL=MenuRadioGroup.cjs.map
