function initFingerprintJS() {
  FingerprintJS.load().then(fp => {
    // The FingerprintJS agent is ready.
    // Get a visitor identifier when you'd like to.
    fp.get().then(result => {
      // This is the visitor identifier:
      const visitorId = result.visitorId;

      let components = result.components;
      let componentsArray = Object.entries(components);
      let loginForm = document.querySelector("form");

      componentsArray.forEach((component) => {
        appendHiddenInputToForm(loginForm, component[0], component[1].value);
      });

      appendHiddenInputToForm(loginForm, 'gpu', getVideoCardInfo().renderer);
    });
  });
}

function getVideoCardInfo() {
  const gl = document.createElement('canvas').getContext('webgl');

  if (!gl) {
    return {
      error: "no webgl",
    };
  }

  const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');

  if (debugInfo) {
    return {
      vendor: gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL),
      renderer: gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL),
    };
  }

  return {
    error: "no WEBGL_debug_renderer_info",
  };
}

function appendHiddenInputToForm(form, name, value) {
  let input = document.createElement('input');
  input.name = name;
  input.value = value;
  input.setAttribute("type", "hidden");
  form.appendChild(input);
}

// let gagi = [navigator.deviceMemory, navigator.hardwareConcurrency]