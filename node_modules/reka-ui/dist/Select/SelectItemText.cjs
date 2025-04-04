'use strict';

const vue = require('vue');
const shared_useForwardExpose = require('../shared/useForwardExpose.cjs');
const Primitive_Primitive = require('../Primitive/Primitive.cjs');
const Select_SelectRoot = require('./SelectRoot.cjs');
const Select_SelectContentImpl = require('./SelectContentImpl.cjs');
const Select_SelectItem = require('./SelectItem.cjs');

const _sfc_main = /* @__PURE__ */ vue.defineComponent({
  ...{
    inheritAttrs: false
  },
  __name: "SelectItemText",
  props: {
    asChild: { type: Boolean },
    as: { default: "span" }
  },
  setup(__props) {
    const props = __props;
    const rootContext = Select_SelectRoot.injectSelectRootContext();
    const contentContext = Select_SelectContentImpl.injectSelectContentContext();
    const itemContext = Select_SelectItem.injectSelectItemContext();
    const { forwardRef, currentElement: itemTextElement } = shared_useForwardExpose.useForwardExpose();
    const optionProps = vue.computed(() => {
      return {
        value: itemContext.value,
        disabled: itemContext.disabled.value,
        textContent: itemTextElement.value?.textContent ?? itemContext.value?.toString() ?? ""
      };
    });
    vue.onMounted(() => {
      if (!itemTextElement.value)
        return;
      itemContext.onItemTextChange(itemTextElement.value);
      contentContext.itemTextRefCallback(
        itemTextElement.value,
        itemContext.value,
        itemContext.disabled.value
      );
      rootContext.onOptionAdd(optionProps.value);
    });
    vue.onBeforeUnmount(() => {
      rootContext.onOptionRemove(optionProps.value);
    });
    return (_ctx, _cache) => {
      return vue.openBlock(), vue.createBlock(vue.unref(Primitive_Primitive.Primitive), vue.mergeProps({
        id: vue.unref(itemContext).textId,
        ref: vue.unref(forwardRef)
      }, { ...props, ..._ctx.$attrs }), {
        default: vue.withCtx(() => [
          vue.renderSlot(_ctx.$slots, "default")
        ]),
        _: 3
      }, 16, ["id"]);
    };
  }
});

exports._sfc_main = _sfc_main;
//# sourceMappingURL=SelectItemText.cjs.map
