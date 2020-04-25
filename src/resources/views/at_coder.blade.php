<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Testing Tool for AtCoder</title>
    <link rel="stylesheet" href="{{ url('/lib/codemirror/codemirror.css') }}">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #ddd;
            overflow: hidden;
        }
        .grid {
            display: grid;
            grid-template: "main-header sub-header" 30px "main sub" 1fr / min-content 1fr;
        }
        .grid-main-header {
            grid-area: main-header;
            border-bottom: 1px solid #aaa;
            padding-left: 3px;
            overflow: hidden;
        }
        .grid-main {
            grid-area: main;
            min-width: 300px;
            max-width: calc(100vw - 300px);
            overflow: auto;
            resize: horizontal;
        }
        .grid-sub-header {
            grid-area: sub-header;
            border-left: 1px solid #aaa;
            border-bottom: 1px solid #aaa;
            padding-left: 3px;
            overflow: hidden;
        }
        .grid-sub {
            grid-area: sub;
            border-left: 1px solid #aaa;
            overflow: auto;
        }
        .box-label {
            position: relative;
        }
        .box-label::before {
            display: inline-block;
            content: " ";
            position: relative;
            top: 10px;
            border: 8px solid transparent;
            border-top: 14px solid black;
            width: 0;
            height: 0;
            pointer-events: none;
            transform-origin: 8px 6px;
            transition: transform 200ms ease;
        }
        .box-label.app-close::before {
            transform: rotate(-90deg);
        }
        .box {
            transition: all 200ms ease;
            min-height: 40px;
            overflow: hidden;
        }
        .box-label.app-close+.box {
            height: 0!important;
            min-height: 0;
        }
        .editor-box {
            height: 300px;
            resize: vertical;
        }
        .result {
            box-sizing: border-box;
            width: 100%;
            resize: vertical;
            background: #def;
        }
        .check-label {
            display: block;
        }
        #copySubmitCode {
            position: absolute;
            left: 125px;
            margin-top: 8px;
        }
        .loading {
            position: relative;
        }
        .loading-filter {
            display: none;
            z-index: 100;
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.25);
        }
        .loading>.loading-filter {
            display: block;
        }
        .loading-position {
            position: relative;
            width: 50%;
            height: 50%;
        }
        .loading-animation {
            position: absolute;
            right: 0;
            bottom: 0;
            margin: -0.5em;
            border-radius: 50%;
            width: 1em;
            height: 1em;
            font-size: 90px;
            color: #ffffff;
            overflow: hidden;
            text-indent: -9999em;
            transform: translateZ(0);
            animation: loading-keyframes 1.7s infinite ease, rotate-keyframes 1.7s infinite ease;
        }
        @keyframes loading-keyframes {
            0% {
                box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
            }
            5%,
            95% {
                box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
            }
            10%,
            59% {
                box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
            }
            20% {
                box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
            }
            38% {
                box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
            }
            100% {
                box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
            }
        }
        @keyframes rotate-keyframes {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body class="grid">
<div class="grid-main-header">
    <small>⌘+S : Run test cases and create a submit code.</small>
</div>
<div class="grid-main">
    <div class="code">
        <label class="box-label" for="mainEditor">Main Code</label>
        <div class="box editor-box">
            <textarea id="mainEditor">{{ file_get_contents(resource_path('assets/php/main_editor.php')) }}</textarea>
        </div>
        <label class="box-label" for="devEditor">Additional Code for Self-Check</label>
        <div class="box editor-box">
            <textarea id="devEditor">{{ file_get_contents(resource_path('assets/php/dev_editor.php')) }}</textarea>
        </div>
        <label class="box-label" for="prdEditor">Additional Code for Submit</label>
        <div class="box editor-box">
            <textarea id="prdEditor">{{ file_get_contents(resource_path('assets/php/prd_editor.php')) }}</textarea>
        </div>
        <hr>
        <button type="button" id="copySubmitCode">copy</button>
        <label class="box-label" for="submitCode">Submit Code</label>
        <div class="box">
            <textarea class="result" id="submitCode" readonly></textarea>
        </div>
    </div>
</div>
<div class="grid-sub-header">
    <button type="button" id="addTestCase">Add a test case</button>
</div>
<div id="testCaseBox" class="grid-sub">
    <label class="check-label"><input type="checkbox" id="bailOut" checked><small>Stop immediately if one test case fails.</small></label>
    <label class="check-label"><input type="checkbox" id="onlyFail" checked><small>Run the only failed test cases.</small></label>
    {{-- dummy --}}
    <div style="display: none;" class="loading">
        <div class="loading-filter"><div class="loading-position"><div class="loading-animation"></div></div></div>
        <label class="box-label app-close" for="testCaseEditor0">Test Case 0</label>
        <div class="box editor-box">
            <textarea id="testCaseEditor0"></textarea>
        </div>
        <label class="box-label" for="result0">Test Result 0</label>
        <div class="box">
            <textarea class="result" id="result0" readonly></textarea>
        </div>
    </div>
</div>
<script src="{{ url('/lib/codemirror/codemirror.js') }}"></script>
<script src="{{ url('/lib/codemirror/php.js') }}"></script>
<script src="{{ url('/lib/codemirror/clike.js') }}"></script>
<script src="{{ url('/lib/codemirror/css.js') }}"></script>
<script src="{{ url('/lib/codemirror/htmlmixed.js') }}"></script>
<script src="{{ url('/lib/codemirror/javascript.js') }}"></script>
<script src="{{ url('/lib/codemirror/xml.js') }}"></script>
<script>
"use strict";

const headers = {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": "{{ csrf_token() }}",
    "Content-Type": "application/json; charset=utf-8",
};

function createEditor(id) {
    return CodeMirror.fromTextArea(document.getElementById(id), {
        mode: "php",
        lineNumbers: true,
        indentUnit: 4,
        extraKeys: {"Cmd-S": submit},
    });
}

function submit() {
    const mainCode  = mainEditor.doc.getValue();
    const devCode   = devEditor.doc.getValue();
    const prdCode   = prdEditor.doc.getValue();
    const testCases = [];
    const bailOut   = document.getElementById("bailOut").checked;
    const onlyFail  = document.getElementById("onlyFail").checked;
    let failedOnce  = false;

    for (const id in testCaseSet) {
        const testCase = testCaseSet[id];

        testCases.push(testCase);

        if (onlyFail && testCase.passed) {
            continue;
        }

        testCase.startLoading();
    }

    document.getElementById("submitCode").value = prdCode + mainCode.slice(5);

    runTestCodes().then(() => {
        console.log("end", {
            main_code: mainCode,
            dev_code: devCode,
            prd_code: prdCode,
            test_codes: testCases.map(testCase => testCase.getCode()),
            bail_out: bailOut,
            only_fail: onlyFail,
        });
    });

    function runTestCodes() {
        return next(0);
    }

    function next(i) {
        const testCase = testCases[i];
        if (!testCase) {
            return Promise.resolve();
        }

        if (onlyFail && testCase.passed) {
            return next(i + 1);
        }

        if (bailOut && failedOnce) {
            testCase.setResult({
                passed: false,
                message: "stopped.",
            });
            testCase.finishLoading();

            return next(i + 1);
        }

        return executeSubmit(testCase).then(() => {
            testCase.finishLoading();

            if (!testCase.passed) {
                failedOnce = true;
            }

            return next(i + 1);
        });
    }

    function executeSubmit(testCase) {
        return fetch("/", {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                main_code: mainCode,
                dev_code: devCode,
                test_code: testCase.getCode(),
            }),
        }).then(
            response => response.json()
        ).then(data => {
            console.log(data);

            return {passed: false, message: "dummy message"};
        }).catch(error => {
            console.log(error);

            return {passed: false, message: error};
        }).then(
            result => testCase.setResult(result)
        );
    }
}

const resizeHeight = (function () {
    let timeoutId;
    let entry;

    function execute() {
        timeoutId = null;
        entry.target.querySelector(".CodeMirror").style.height = entry.contentRect.height + "px";
    }

    return function (resizeEntry) {
        entry = resizeEntry;

        if (!timeoutId) {
            timeoutId = setTimeout(execute, 100);
        }
    }
})();
const resizeObserver = new ResizeObserver(entries => {
    for (const resizeEntry of entries) {
        resizeHeight(resizeEntry);
    }
});

const testCaseSet  = {};
let lastTestCaseId = 0;
class TestCase {
    constructor() {
        const id      = ++lastTestCaseId;
        const element = document.createElement("div");

        element.insertAdjacentHTML("beforeend", `<hr>
<div class="loading-filter"><div class="loading-position"><div class="loading-animation"></div></div></div>
<label class="box-label" for="testCaseEditor${ id }">Test Case ${ id } (TODO: drop)</label>
<div class="box editor-box">
    <textarea id="testCaseEditor${ id }">{{ file_get_contents(resource_path('assets/php/case_editor.php')) }}</textarea>
</div>
<label class="box-label" for="result${ id }">Test Result ${ id }</label>
<div class="box">
    <textarea class="result" id="result${ id }" readonly></textarea>
</div>
`
        );
        document.getElementById("testCaseBox").appendChild(element);

        element.querySelectorAll(".box-label").forEach(
            element => element.addEventListener("click", event => event.target.classList.toggle("app-close"), false)
        );

        this.id      = id;
        this.element = element;
        this.editor  = createEditor(`testCaseEditor${ id }`);
        this.passed  = false;

        testCaseSet[id] = this;
        resizeObserver.observe(element.querySelector(".editor-box"));
    }

    static create() {
        return new TestCase();
    }

    getCode() {
        return this.editor.doc.getValue();
    }

    startLoading() {
        this.element.classList.add("loading");
    }

    finishLoading() {
        this.element.classList.remove("loading");
    }

    setResult(result) {
        this.passed = result.passed;
        // TODO: 色付け
        this.element.querySelector(".result").value = result.message;
    }

    drop() {
        resizeObserver.unobserve(this.element.querySelector(".editor-box"));
        delete testCaseSet[this.id];

        this.editor.toTextArea();
        this.element.remove();
    }
}

// initialize
const mainEditor = createEditor("mainEditor");
const devEditor  = createEditor("devEditor");
const prdEditor  = createEditor("prdEditor");

document.querySelectorAll(".box-label").forEach(
    element => element.addEventListener("click", event => event.target.classList.toggle("app-close"), false)
);
document.querySelectorAll(".editor-box").forEach(
    element => resizeObserver.observe(element)
);
document.getElementById("addTestCase").addEventListener("click", TestCase.create, false);

document.getElementById("copySubmitCode").addEventListener("click", () => {
    document.getElementById("submitCode").select();
    document.execCommand("copy");
}, false);

window.addEventListener("keydown", event => {
    // press Cmd+S or Ctrl+S, then submit
    if (event.keyCode === 83 && (event.metaKey || event.ctrlKey)) {
        event.preventDefault();
        submit();
    }
}, false);

</script>
</body>
</html>
