<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Testing Tool for AtCoder</title>
    <link rel="stylesheet" href="{{url('/lib/codemirror/codemirror.css')}}">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #ddd;
            overflow: hidden;
        }
        textarea {
            display: block;
            width: 100%;
            height: 100%;
            background: #def;
        }
        button {
            border-radius: 6px;
            line-height: 1.5em;
        }
        .grid {
            display: grid;
            grid-template: "header header" 30px "main sub" 1fr / min-content 1fr;
        }
        .grid-header {
            grid-area: header;
            border-bottom: 1px solid #aaa;
            padding-left: 3px;
            overflow: hidden;
        }
        #codeList {
            min-width: 16em;
        }
        #codeName {
            width: 16em;
        }
        .grid-main {
            grid-area: main;
            position: relative;
            min-width: 300px;
            max-width: calc(100vw - 300px);
            width: 50vw;
            overflow: auto;
            resize: horizontal;
        }
        .grid-sub {
            grid-area: sub;
            border-left: 1px solid #aaa;
            overflow: auto;
        }
        .inline-box {
            display: inline-block;
            height: 100%;
        }
        .inline-box+.inline-box {
            margin-left: 5px;
            border-left: 1px solid #ccc;
            padding-left: 10px;
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
        .hidden-label {
            display: block;
            height: 1px;
            overflow: hidden;
        }
        .box {
            transition: all 200ms ease;
            min-height: 30px;
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
        .float-button {
            z-index: 1;
            position: absolute;
            right: 5px;
            margin-top: 5px;
            width: 2em;
            padding: 1px 0;
        }
        #copySubmitCode {
            width: 11em;
        }
        .result {
            box-sizing: border-box;
            resize: vertical;
        }
        .result.app-passed {
            background: #bfd;
        }
        .result.app-failed {
            background: #fdd;
        }
        .check-label {
            display: block;
        }
        .app-loading {
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
        .app-loading>.loading-filter {
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
            animation: 1.7s ease infinite loading-keyframes, 1.7s ease infinite rotate-keyframes;
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
        .toast-box {
            z-index: 101;
            position: absolute;
            right: 0;
            bottom: 1px;
            left: 0;
            margin: auto;
            width: calc(100% - 10em);
            min-width: 50%;
            overflow: hidden;
        }
        .toast {
            box-sizing: border-box;
            border: 1px solid #afa;
            border-radius: 0.5em;
            padding: 0.5em;
            height: 2em;
            color: #048;
            background: #cfc;
            overflow: hidden;
            animation: 1s ease-out 4s 1 both fade-out;
        }
        @keyframes fade-out {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }
    </style>
</head>
<body class="grid">
<div class="grid-header">
    <div class="inline-box">
        <select id="codeList" title="codeList">
            <option value=""></option>
            <option value="(previous codes)" selected>(previous codes)</option>
            <option value="(default codes)">(default codes)</option>
        </select>
        <button type="button" id="openCodes">Open codes</button>
    </div>
    <div class="inline-box">
        <input type="text" id="codeName" title="code name" placeholder="code name">
        <button type="button" id="saveCodes">Save codes (⌘+S)</button>
        <button type="button" id="deleteCodes">Delete codes</button>
    </div>
    <div class="inline-box"><button type="button" id="runTestCases">Run test cases (⌘+R)</button></div>
</div>
<div class="grid-main">
    <button type="button" class="float-button" id="copySubmitCode">Copy a submit code</button>
    <label class="box-label" for="mainEditor">Main Code</label>
    <div class="box editor-box"><textarea id="mainEditor"></textarea></div>
    <label class="box-label" for="devEditor">Additional Code for Test</label>
    <div class="box editor-box"><textarea id="devEditor"></textarea></div>
    <label class="box-label" for="prdEditor">Additional Code for Submit</label>
    <div class="box editor-box"><textarea id="prdEditor"></textarea></div>
    <label class="hidden-label" for="submitCode"><textarea class="result" id="submitCode" readonly></textarea></label>
</div>
<div id="testCaseBox" class="grid-sub">
    <div>
        <button type="button" class="float-button" id="addTestCase">＋</button>
        <label class="box-label app-close" for="testCases">Test Cases</label>
        <div class="box" id="testCases">
            <label class="check-label"><input type="checkbox" id="bailOut" checked><small>Stop immediately if one test case fails.</small></label>
            <label class="check-label"><input type="checkbox" id="onlyFail" checked><small>Run the only failed test cases.</small></label>
            <label class="check-label">Time Limit: <input type="number" id="timeLimit" min="1" max="60" value="2"><small>seconds</small></label>
            <label class="check-label">Memory Limit: <input type="number" id="memoryLimit" min="1" max="512" value="256"><small>MB</small></label>
        </div>
    </div>
    {{-- dummy --}}
    <div style="display: none;">
        {{-- Test Case 0 --}}
        <div class="app-loading">
            <div class="loading-filter"><div class="loading-position"><div class="loading-animation"></div></div></div>
            <button class="float-button delete-button">−</button>
            <label class="box-label app-close" for="testCaseEditor0">Test Case 0</label>
            <div class="box editor-box">
                <textarea id="testCaseEditor0" readonly></textarea>
            </div>
            <label class="box-label" for="result0">Test Result 0</label>
            <div class="box">
                <textarea class="result app-passed app-failed" id="result0" readonly></textarea>
            </div>
        </div>
        {{-- Toast --}}
        <div class="toast"></div>
    </div>
</div>
<div class="toast-box"></div>
<script src="{{url('/lib/codemirror/codemirror.js')}}"></script>
<script src="{{url('/lib/codemirror/php.js')}}"></script>
<script src="{{url('/lib/codemirror/clike.js')}}"></script>
<script src="{{url('/lib/codemirror/css.js')}}"></script>
<script src="{{url('/lib/codemirror/htmlmixed.js')}}"></script>
<script src="{{url('/lib/codemirror/javascript.js')}}"></script>
<script src="{{url('/lib/codemirror/xml.js')}}"></script>
<script>
"use strict";

const headers = {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": "{{csrf_token()}}",
    "Content-Type": "application/json; charset=utf-8",
};

function createEditor(id) {
    return CodeMirror.fromTextArea(document.getElementById(id), {
        mode: "php",
        lineNumbers: true,
        indentUnit: 4,
    });
}

function openCodes(silent) {
    const codeName = document.getElementById("codeList").value;
    if (codeName === "") {
        document.getElementById("codeList").focus();

        return;
    }

    const codes    = codeRepository.getCodes(codeName);

    TestCase.dropAll();
    document.querySelectorAll(".testCase").forEach(element => element.remove());

    document.getElementById("codeName").value = codeName;
    mainEditor.getDoc().setValue(codes.mainCode);
    devEditor.getDoc().setValue(codes.devCode);
    prdEditor.getDoc().setValue(codes.prdCode);

    for (const code of codes.testCodes) {
        TestCase.create(code);
    }

    if (!silent) {
        Toast.create(`${codeName} loaded.`);
    }
}

function hasChangedCodes(oldCodes) {
    if (oldCodes === null) {
        return true;
    }

    const newCodes = getEditorCodes();

    return oldCodes.testCodes.length !== newCodes.testCodes.length
        || oldCodes.mainCode !== newCodes.mainCode
        || oldCodes.devCode !== newCodes.devCode
        || oldCodes.prdCode !== newCodes.prdCode
        || oldCodes.testCodes.some((code, index) => code !== newCodes.testCodes[index])
        ;
}

function getEditorCodes() {
    const testCodes = [];

    for (const id in testCaseSet) {
        testCodes.push(testCaseSet[id].getCode());
    }

    return {
        mainCode: mainEditor.getDoc().getValue(),
        devCode: devEditor.getDoc().getValue(),
        prdCode: prdEditor.getDoc().getValue(),
        testCodes: testCodes,
    }
}

function saveCodes() {
    const element  = document.getElementById("codeName");
    const codeName = element.value;
    const oldCodes = codeRepository.getCodes(codeName);

    if (!hasChangedCodes(oldCodes)) {
        Toast.create("not changed.");

        return;
    }

    try {
        codeRepository.saveCodes(codeName, getEditorCodes());
    } catch (e) {
        if (e && e instanceof ReservedError) {
            element.value = "";
            element.focus();

            return;
        }
    }

    if (oldCodes === null) {
        addCodeName(codeName);
    }
    document.getElementById("codeList").value = codeName;

    if (Math.max.apply(null, Object.keys(testCaseSet)) !== getEditorCodes().testCodes.length) {
        setTimeout(() => openCodes(true), 1);
    }

    Toast.create("saved.");
}

function deleteCodes() {
    const element  = document.getElementById("codeName");
    const codeName = element.value;
    const oldCodes = codeRepository.getCodes(codeName);
    if (oldCodes === null) {
        Toast.create("nothing to delete.");

        return;
    }

    try {
        codeRepository.deleteCodes(codeName);
    } catch (e) {
        Toast.create("cannot delete system data.");

        return;
    }

    document.getElementById("codeList").value = "";

    const options = document.getElementById("codeList").children;
    Array.prototype.some.call(options, element => {
        if (element.value !== codeName) {
            return false;
        }

        element.remove();

        return true;
    });

    element.value = "";
    element.focus();

    Toast.create("deleted.");
}

function addCodeName(codeName) {
    const element = document.createElement("option");

    element.value       = codeName;
    element.textContent = codeName;

    document.getElementById("codeList").appendChild(element);
}

function runTestCases() {
    const testCases = [];
    const mainCode = mainEditor.getDoc().getValue();
    const devCode = devEditor.getDoc().getValue();
    const bailOut = document.getElementById("bailOut").checked;
    const onlyFail = document.getElementById("onlyFail").checked;
    const timeLimit = +document.getElementById("timeLimit").value;
    const memoryLimit = +document.getElementById("memoryLimit").value;
    let failedOnce = false;

    for (const id in testCaseSet) {
        const testCase = testCaseSet[id];

        if (onlyFail && testCase.isPassed()) {
            continue;
        }

        testCases.push(testCase);
        testCase.startLoading();
    }

    next(0);

    function next(i) {
        const testCase = testCases[i];
        if (!testCase) {
            return Promise.resolve();
        }

        if (bailOut && failedOnce) {
            testCase.setResult({passed: null, message: "stopped."});
            testCase.finishLoading();

            return next(i + 1);
        }

        return executeSubmit(testCase)
            .then(() => {
                testCase.finishLoading();

                if (!testCase.isPassed()) {
                    failedOnce = true;
                }

                return next(i + 1);
            });
    }

    function executeSubmit(testCase) {
        return fetch("/api/test-case", {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                main_code: mainCode,
                dev_code: devCode,
                test_code: testCase.getCode(),
                time_limit: timeLimit,
                memory_limit: memoryLimit,
            }),
        })
            .then(
                response => response.json()
            ).then(
                data => ({passed: data.passed, message: data.message})
            ).catch(error => {
                console.error("failed to executeSubmit", error);

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
    constructor(code) {
        const id      = ++lastTestCaseId;
        const element = document.createElement("div");

        element.classList.add("testCase");
        element.insertAdjacentHTML("beforeend", `<hr>
<div class="loading-filter"><div class="loading-position"><div class="loading-animation"></div></div></div>
<button class="float-button delete-button">−</button>
<label class="box-label" for="testCaseEditor${id}">Test Case ${id}</label>
<div class="box editor-box">
    <textarea id="testCaseEditor${id}" readonly></textarea>
</div>
<label class="box-label" for="result${id}">Test Result ${id}</label>
<div class="box">
    <textarea class="result" id="result${id}" readonly></textarea>
</div>
`
        );
        document.getElementById("testCaseBox").appendChild(element);

        const drop = () => {
            resizeObserver.unobserve(this.element.querySelector(".editor-box"));
            delete testCaseSet[this.id];

            this.editor.toTextArea();
            this.editor = null;

            const deleteButton = this.element.querySelector(".delete-button");
            deleteButton.removeEventListener("click", drop, false);
            deleteButton.remove();

            this.element.querySelectorAll(".box-label").forEach(element => element.classList.add("app-close"));

            this.element = null;

            this.drop = null;
        };

        element.querySelector(".delete-button").addEventListener("click", drop, false);
        element.querySelectorAll(".box-label").forEach(
            element => element.addEventListener("click", event => event.target.classList.toggle("app-close"), false)
        );
        document.getElementById(`testCaseEditor${id}`).value = code;

        this.id         = id;
        this.element    = element;
        this.editor     = createEditor(`testCaseEditor${id}`);
        this.passed     = false;
        this.testedCode = null;
        this.drop       = drop;

        testCaseSet[id] = this;
        resizeObserver.observe(element.querySelector(".editor-box"));
    }

    static create(code) {
        return new TestCase(code);
    }

    static dropAll() {
        for (const id in testCaseSet) {
            testCaseSet[id].drop();
        }

        lastTestCaseId = 0;
    }

    getCode() {
        return this.editor.getDoc().getValue();
    }

    isPassed() {
        return this.passed && this.getCode() === this.testedCode;
    }

    startLoading() {
        this.element.classList.add("app-loading");
    }

    finishLoading() {
        this.element.classList.remove("app-loading");
    }

    setResult(result) {
        const element = this.element.querySelector(".result");
        element.value = result.message;
        element.classList.remove("app-passed", "app-failed");
        if (result.passed === true) {
            element.classList.add("app-passed");
        } else if (result.passed === false) {
            element.classList.add("app-failed");
        }

        this.passed     = result.passed || false;
        this.testedCode = this.getCode();
    }
}

class CodeRepository {
    constructor() {
        this.prefixKey    = "code_";
        this.defaultName  = "(default codes)";
        this.previousName = "(previous codes)";
        this.testCode     = `{!!file_get_contents(resource_path('assets/php/default/test_case.php'))!!}`;
        this.defaultCodes = {
            mainCode: `{!!file_get_contents(resource_path('assets/php/default/main_code.php'))!!}`,
            devCode: `{!!file_get_contents(resource_path('assets/php/default/additional_code_for_test.php'))!!}`,
            prdCode: `{!!file_get_contents(resource_path('assets/php/default/additional_code_for_submit.php'))!!}`,
            testCodes: [this.testCode],
        };
    }

    getCodes(codeName) {
        const json = localStorage.getItem(this.prefixKey + codeName);

        if (json) {
            return JSON.parse(json);
        }

        if (codeName === this.defaultName || codeName === this.previousName) {
            return this.defaultCodes;
        }

        return null;
    }

    saveCodes(codeName, codes) {
        if (codeName === "" || codeName === this.defaultName || codeName === this.previousName) {
            throw new ReservedError(codeName);
        }

        localStorage.setItem(this.prefixKey + codeName, JSON.stringify(codes));
    }

    deleteCodes(codeName) {
        if (codeName === this.defaultName || codeName === this.previousName) {
            throw new ReservedError(codeName);
        }

        localStorage.removeItem(this.prefixKey + codeName);
    }

    savePreviousCodes(codes) {
        localStorage.setItem(this.prefixKey + this.previousName, JSON.stringify(codes));
    }

    getDefaultTestCode() {
        return this.testCode;
    }

    getCodeNames() {
        const codeNames = [];

        for (let i = 0, j = localStorage.length; i < j; ++i) {
            const key = localStorage.key(i);
            if (!key.startsWith(this.prefixKey)) {
                continue;
            }

            const codeName = key.slice(this.prefixKey.length);
            if (codeName === this.previousName) {
                continue;
            }

            codeNames.push(codeName);
        }

        return codeNames;
    }
}

class ReservedError extends Error {
}

class Toast {
    constructor(message) {
        const element = document.createElement("div");
        const drop    = () => {
            clearTimeout(timeoutId);

            element.removeEventListener("click", drop, false);
            element.remove();
        };
        const timeoutId = setTimeout(drop, 5000);

        element.classList.add("toast");
        element.textContent = message;
        document.querySelector(".toast-box").appendChild(element);
        element.addEventListener("click", drop, false);
    }

    static create(message) {
        return new Toast(message);
    }
}

// initialize
const codeRepository = new CodeRepository();
const mainEditor     = createEditor("mainEditor");
const devEditor      = createEditor("devEditor");
const prdEditor      = createEditor("prdEditor");

document.querySelectorAll(".box-label").forEach(
    element => element.addEventListener("click", event => event.target.classList.toggle("app-close"), false)
);

document.querySelectorAll(".editor-box").forEach(
    element => resizeObserver.observe(element)
);

document.getElementById("openCodes").addEventListener("click", openCodes, false);
document.getElementById("saveCodes").addEventListener("click", saveCodes, false);
document.getElementById("deleteCodes").addEventListener("click", deleteCodes, false);
document.getElementById("runTestCases").addEventListener("click", runTestCases, false);

document.getElementById("copySubmitCode").addEventListener("click", () => {
    document.getElementById("submitCode").value = prdEditor.getDoc().getValue() + mainEditor.getDoc().getValue().slice(5);
    document.getElementById("submitCode").select();
    document.execCommand("copy");
}, false);

document.getElementById("addTestCase").addEventListener("click", () => TestCase.create(codeRepository.getDefaultTestCode()), false);

document.addEventListener("keydown", event => {
    if (event.metaKey || event.ctrlKey) {
        switch (event.keyCode) {
            case 82: // R
                runTestCases();

                break;
            case 83: // S
                saveCodes();

                break;
            default:

                return;
        }

        event.preventDefault();
        event.stopPropagation();

        return false;
    }
}, false);

window.addEventListener("beforeunload", () => codeRepository.savePreviousCodes(getEditorCodes()), false);

codeRepository.getCodeNames().forEach(addCodeName);
openCodes();

</script>
</body>
</html>
