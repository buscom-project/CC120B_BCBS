'use strict';

const vue = require('vue');
const Popper_PopperContent = require('../Popper/PopperContent.cjs');
const DismissableLayer_DismissableLayer = require('../DismissableLayer/DismissableLayer.cjs');
const shared = require('@vueuse/shared');
const shared_useForwardProps = require('../shared/useForwardProps.cjs');
const shared_useForwardExpose = require('../shared/useForwardExpose.cjs');
const shared_useFocusGuards = require('../shared/useFocusGuards.cjs');
const FocusScope_FocusScope = require('../FocusScope/FocusScope.cjs');
const Popover_PopoverRoot = require('./PopoverRoot.cjs');

const _sfc_main = /* @__PURE__ */ vue.defineComponent({
  __name: "PopoverContentImpl",
  props: {
    trapFocus: { type: Boolean },
    side: {},
    sideOffset: {},
    align: {},
    alignOffset: {},
    avoidCollisions: { type: Boolean },
    collisionBoundary: {},
    collisionPadding: {},
    arrowPadding: {},
    sticky: {},
    hideWhenDetached: { type: Boolean },
    positionStrategy: {},
    updatePositionStrategy: {},
    disableUpdateOnLayoutShift: { type: Boolean },
    prioritizePosition: { type: Boolean },
    reference: {},
    asChild: { type: Boolean },
    as: {},
    disableOutsidePointerEvents: { type: Boolean }
  },
  emits: ["escapeKeyDown", "pointerDownOutside", "focusOutside", "interactOutside", "openAutoFocus", "closeAutoFocus"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emits = __emit;
    const forwarded = shared_useForwardProps.useForwardProps(shared.reactiveOmit(props, "trapFocus", "disableOutsidePointerEvents"));
    const { forwardRef } = shared_useForwardExpose.useForwardExpose();
    const rootContext = Popover_PopoverRoot.injectPopoverRootContext();
    shared_useFocusGuards.useFocusGuards();
    return (_ctx, _cache) => {
      return vue.openBlock(), vue.createBlock(vue.unref(FocusScope_FocusScope._sfc_main), {
        "as-child": "",
        loop: "",
        trapped: _ctx.trapFocus,
        onMountAutoFocus: _cache[5] || (_cache[5] = ($event) => emits("openAutoFocus", $event)),
        onUnmountAutoFocus: _cache[6] || (_cache[6] = ($event) => emits("closeAutoFocus", $event))
      }, {
        default: vue.withCtx(() => [
          vue.createVNode(vue.unref(DismissableLayer_DismissableLayer._sfc_main), {
            "as-child": "",
            "disable-outside-pointer-events": _ctx.disableOutsidePointerEvents,
            onPointerDownOutside: _cache[0] || (_cache[0] = ($event) => emits("pointerDownOutside", $event)),
            onInteractOutside: _cache[1] || (_cache[1] = ($event) => emits("interactOutside", $event)),
            onEscapeKeyDown: _cache[2] || (_cache[2] = ($event) => emits("escapeKeyDown", $event)),
            onFocusOutside: _cache[3] || (_cache[3] = ($event) => emits("focusOutside", $event)),
            onDismiss: _cache[4] || (_cache[4] = ($event) => vue.unref(rootContext).onOpenChange(false))
          }, {
            default: vue.withCtx(() => [
              vue.createVNode(vue.unref(Popper_PopperContent._sfc_main), vue.mergeProps(vue.unref(forwarded), {
                id: vue.unref(rootContext).contentId,
                ref: vue.unref(forwardRef),
                "data-state": vue.unref(rootContext).open.value ? "open" : "closed",
                "aria-labelledby": vue.unref(rootContext).triggerId,
                style: {
                  "--reka-popover-content-transform-origin": "var(--reka-popper-transform-origin)",
                  "--reka-popover-content-available-width": "var(--reka-popper-available-width)",
                  "--reka-popover-content-available-height": "var(--reka-popper-available-height)",
                  "--reka-popover-trigger-width": "var(--reka-popper-anchor-width)",
                  "--reka-popover-trigger-height": "var(--reka-popper-anchor-height)"
                },
                role: "dialog"
              }), {
                default: vue.withCtx(() => [
                  vue.renderSlot(_ctx.$slots, "default")
                ]),
                _: 3
              }, 16, ["id", "data-state", "aria-labelledby"])
            ]),
            _: 3
          }, 8, ["disable-outside-pointer-events"])
        ]),
        _: 3
      }, 8, ["trapped"]);
    };
  }
});

exports._sfc_main = _sfc_main;
//# sourceMappingURL=PopoverContentImpl.cjs.map
