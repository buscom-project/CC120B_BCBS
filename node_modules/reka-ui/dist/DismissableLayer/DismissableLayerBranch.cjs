'use strict';

const vue = require('vue');
const shared_useForwardExpose = require('../shared/useForwardExpose.cjs');
const DismissableLayer_DismissableLayer = require('./DismissableLayer.cjs');
const Primitive_Primitive = require('../Primitive/Primitive.cjs');

const _sfc_main = /* @__PURE__ */ vue.defineComponent({
  __name: "DismissableLayerBranch",
  props: {
    asChild: { type: Boolean },
    as: {}
  },
  setup(__props) {
    const props = __props;
    const { forwardRef, currentElement } = shared_useForwardExpose.useForwardExpose();
    vue.onMounted(() => {
      DismissableLayer_DismissableLayer.context.branches.add(currentElement.value);
    });
    vue.onUnmounted(() => {
      DismissableLayer_DismissableLayer.context.branches.delete(currentElement.value);
    });
    return (_ctx, _cache) => {
      return vue.openBlock(), vue.createBlock(vue.unref(Primitive_Primitive.Primitive), vue.mergeProps({ ref: vue.unref(forwardRef) }, props), {
        default: vue.withCtx(() => [
          vue.renderSlot(_ctx.$slots, "default")
        ]),
        _: 3
      }, 16);
    };
  }
});

exports._sfc_main = _sfc_main;
//# sourceMappingURL=DismissableLayerBranch.cjs.map
