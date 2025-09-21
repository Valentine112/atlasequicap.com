/*!
* inputmask.js
* https://github.com/RobinHerbots/Inputmask
* Copyright (c) 2010 - 2017 Robin Herbots
* Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
* Version: 3.3.11
*/

!function(factory) {
    "function" == typeof define && define.amd ? define([ "./dependencyLibs/inputmask.dependencyLib", "./global/window", "./global/document" ], factory) : "object" == typeof exports ? module.exports = factory(require("./dependencyLibs/inputmask.dependencyLib"), require("./global/window"), require("./global/document")) : window.Inputmask = factory(window.dependencyLib || jQuery, window, document);
}(function($, window, document, undefined) {
    function Inputmask(alias, options, internal) {
        if (!(this instanceof Inputmask)) return new Inputmask(alias, options, internal);
        this.el = undefined, this.events = {}, this.maskset = undefined, this.refreshValue = !1, 
        !0 !== internal && ($.isPlainObject(alias) ? options = alias : (options = options || {}).alias = alias, 
        this.opts = $.extend(!0, {}, this.defaults, options), this.noMasksCache = options && options.definitions !== undefined, 
        this.userOptions = options || {}, this.isRTL = this.opts.numericInput, resolveAlias(this.opts.alias, options, this.opts));
    }
    function resolveAlias(aliasStr, options, opts) {
        var aliasDefinition = Inputmask.prototype.aliases[aliasStr];
        return aliasDefinition ? (aliasDefinition.alias && resolveAlias(aliasDefinition.alias, undefined, opts), 
        $.extend(!0, opts, aliasDefinition), $.extend(!0, opts, options), !0) : (null === opts.mask && (opts.mask = aliasStr), 
        !1);
    }
    function generateMaskSet(opts, nocache) {
        function generateMask(mask, metadata, opts) {
            var regexMask = !1;
            if (null !== mask && "" !== mask || ((regexMask = null !== opts.regex) ? mask = (mask = opts.regex).replace(/^(\^)(.*)(\$)$/, "$2") : (regexMask = !0, 
            mask = ".*")), 1 === mask.length && !1 === opts.greedy && 0 !== opts.repeat && (opts.placeholder = ""), 
            opts.repeat > 0 || "*" === opts.repeat || "+" === opts.repeat) {
                var repeatStart = "*" === opts.repeat ? 0 : "+" === opts.repeat ? 1 : opts.repeat;
                mask = opts.groupmarker.start + mask + opts.groupmarker.end + opts.quantifiermarker.start + repeatStart + "," + opts.repeat + opts.quantifiermarker.end;
            }
            var masksetDefinition, maskdefKey = regexMask ? "regex_" + opts.regex : opts.numericInput ? mask.split("").reverse().join("") : mask;
            return Inputmask.prototype.masksCache[maskdefKey] === undefined || !0 === nocache ? (masksetDefinition = {
                mask: mask,
                maskToken: Inputmask.prototype.analyseMask(mask, regexMask, opts),
                validPositions: {},
                _buffer: undefined,
                buffer: undefined,
                tests: {},
                metadata: metadata,
                maskLength: undefined
            }, !0 !== nocache && (Inputmask.prototype.masksCache[maskdefKey] = masksetDefinition, 
            masksetDefinition = $.extend(!0, {}, Inputmask.prototype.masksCache[maskdefKey]))) : masksetDefinition = $.extend(!0, {}, Inputmask.prototype.masksCache[maskdefKey]), 
            masksetDefinition;
        }
        if ($.isFunction(opts.mask) && (opts.mask = opts.mask(opts)), $.isArray(opts.mask)) {
            if (opts.mask.length > 1) {
                opts.keepStatic = null === opts.keepStatic || opts.keepStatic;
                var altMask = opts.groupmarker.start;
                return $.each(opts.numericInput ? opts.mask.reverse() : opts.mask, function(ndx, msk) {
                    altMask.length > 1 && (altMask += opts.groupmarker.end + opts.alternatormarker + opts.groupmarker.start), 
                    msk.mask === undefined || $.isFunction(msk.mask) ? altMask += msk : altMask += msk.mask;
                }), altMask += opts.groupmarker.end, generateMask(altMask, opts.mask, opts);
            }
            opts.mask = opts.mask.pop();
        }
        return opts.mask && opts.mask.mask !== undefined && !$.isFunction(opts.mask.mask) ? generateMask(opts.mask.mask, opts.mask, opts) : generateMask(opts.mask, opts.mask, opts);
    }
    function maskScope(actionObj, maskset, opts) {
        function getMaskTemplate(baseOnInput, minimalPos, includeMode) {
            minimalPos = minimalPos || 0;
            var ndxIntlzr, test, testPos, maskTemplate = [], pos = 0, lvp = getLastValidPosition();
            do {
                !0 === baseOnInput && getMaskSet().validPositions[pos] ? (test = (testPos = getMaskSet().validPositions[pos]).match, 
                ndxIntlzr = testPos.locator.slice(), maskTemplate.push(!0 === includeMode ? testPos.input : !1 === includeMode ? test.nativeDef : getPlaceholder(pos, test))) : (test = (testPos = getTestTemplate(pos, ndxIntlzr, pos - 1)).match, 
                ndxIntlzr = testPos.locator.slice(), (!1 === opts.jitMasking || pos < lvp || "number" == typeof opts.jitMasking && isFinite(opts.jitMasking) && opts.jitMasking > pos) && maskTemplate.push(!1 === includeMode ? test.nativeDef : getPlaceholder(pos, test))), 
                pos++;
            } while ((maxLength === undefined || pos < maxLength) && (null !== test.fn || "" !== test.def) || minimalPos > pos);
            return "" === maskTemplate[maskTemplate.length - 1] && maskTemplate.pop(), getMaskSet().maskLength = pos + 1, 
            maskTemplate;
        }
        function getMaskSet() {
            return maskset;
        }
        function resetMaskSet(soft) {
            var maskset = getMaskSet();
            maskset.buffer = undefined, !0 !== soft && (maskset.validPositions = {}, maskset.p = 0);
        }
        function getLastValidPosition(closestTo, strict, validPositions) {
            var before = -1, after = -1, valids = validPositions || getMaskSet().validPositions;
            closestTo === undefined && (closestTo = -1);
            for (var posNdx in valids) {
                var psNdx = parseInt(posNdx);
                valids[psNdx] && (strict || !0 !== valids[psNdx].generatedInput) && (psNdx <= closestTo && (before = psNdx), 
                psNdx >= closestTo && (after = psNdx));
            }
            return -1 !== before && closestTo - before > 1 || after < closestTo ? before : after;
        }
        function stripValidPositions(start, end, nocheck, strict) {
            var i, startPos = start, positionsClone = $.extend(!0, {}, getMaskSet().validPositions), needsValidation = !1;
            for (getMaskSet().p = start, i = end - 1; i >= startPos; i--) getMaskSet().validPositions[i] !== undefined && (!0 !== nocheck && (!getMaskSet().validPositions[i].match.optionality && function(pos) {
                var posMatch = getMaskSet().validPositions[pos];
                if (posMatch !== undefined && null === posMatch.match.fn) {
                    var prevMatch = getMaskSet().validPositions[pos - 1], nextMatch = getMaskSet().validPositions[pos + 1];
                    return prevMatch !== undefined && nextMatch !== undefined;
                }
                return !1;
            }(i) || !1 === opts.canClearPosition(getMaskSet(), i, getLastValidPosition(), strict, opts)) || delete getMaskSet().validPositions[i]);
            for (resetMaskSet(!0), i = startPos + 1; i <= getLastValidPosition(); ) {
                for (;getMaskSet().validPositions[startPos] !== undefined; ) startPos++;
                if (i < startPos && (i = startPos + 1), getMaskSet().validPositions[i] === undefined && isMask(i)) i++; else {
                    var t = getTestTemplate(i);
                    !1 === needsValidation && positionsClone[startPos] && positionsClone[startPos].match.def === t.match.def ? (getMaskSet().validPositions[startPos] = $.extend(!0, {}, positionsClone[startPos]), 
                    getMaskSet().validPositions[startPos].input = t.input, delete getMaskSet().validPositions[i], 
                    i++) : positionCanMatchDefinition(startPos, t.match.def) ? !1 !== isValid(startPos, t.input || getPlaceholder(i), !0) && (delete getMaskSet().validPositions[i], 
                    i++, needsValidation = !0) : isMask(i) || (i++, startPos--), startPos++;
                }
            }
            resetMaskSet(!0);
        }
        function determineTestTemplate(tests, guessNextBest) {
            for (var testPos, testPositions = tests, lvp = getLastValidPosition(), lvTest = getMaskSet().validPositions[lvp] || getTests(0)[0], lvTestAltArr = lvTest.alternation !== undefined ? lvTest.locator[lvTest.alternation].toString().split(",") : [], ndx = 0; ndx < testPositions.length && (!((testPos = testPositions[ndx]).match && (opts.greedy && !0 !== testPos.match.optionalQuantifier || (!1 === testPos.match.optionality || !1 === testPos.match.newBlockMarker) && !0 !== testPos.match.optionalQuantifier) && (lvTest.alternation === undefined || lvTest.alternation !== testPos.alternation || testPos.locator[lvTest.alternation] !== undefined && checkAlternationMatch(testPos.locator[lvTest.alternation].toString().split(","), lvTestAltArr))) || !0 === guessNextBest && (null !== testPos.match.fn || /[0-9a-bA-Z]/.test(testPos.match.def))); ndx++) ;
            return testPos;
        }
        function getTestTemplate(pos, ndxIntlzr, tstPs) {
            return getMaskSet().validPositions[pos] || determineTestTemplate(getTests(pos, ndxIntlzr ? ndxIntlzr.slice() : ndxIntlzr, tstPs));
        }
        function getTest(pos) {
            return getMaskSet().validPositions[pos] ? getMaskSet().validPositions[pos] : getTests(pos)[0];
        }
        function positionCanMatchDefinition(pos, def) {
            for (var valid = !1, tests = getTests(pos), tndx = 0; tndx < tests.length; tndx++) if (tests[tndx].match && tests[tndx].match.def === def) {
                valid = !0;
                break;
            }
            return valid;
        }
        function getTests(pos, ndxIntlzr, tstPs) {
            function resolveTestFromToken(maskToken, ndxInitializer, loopNdx, quantifierRecurse) {
                function handleMatch(match, loopNdx, quantifierRecurse) {
                    function isFirstMatch(latestMatch, tokenGroup) {
                        var firstMatch = 0 === $.inArray(latestMatch, tokenGroup.matches);
                        return firstMatch || $.each(tokenGroup.matches, function(ndx, match) {
                            if (!0 === match.isQuantifier && (firstMatch = isFirstMatch(latestMatch, tokenGroup.matches[ndx - 1]))) return !1;
                        }), firstMatch;
                    }
                    function resolveNdxInitializer(pos, alternateNdx, targetAlternation) {
                        var bestMatch, indexPos;
                        if (getMaskSet().validPositions[pos - 1] && targetAlternation && getMaskSet().tests[pos]) for (var vpAlternation = getMaskSet().validPositions[pos - 1].locator, tpAlternation = getMaskSet().tests[pos][0].locator, i = 0; i < targetAlternation; i++) if (vpAlternation[i] !== tpAlternation[i]) return vpAlternation.slice(targetAlternation + 1);
                        return (getMaskSet().tests[pos] || getMaskSet().validPositions[pos]) && $.each(getMaskSet().tests[pos] || [ getMaskSet().validPositions[pos] ], function(ndx, lmnt) {
                            var alternation = targetAlternation !== undefined ? targetAlternation : lmnt.alternation, ndxPos = lmnt.locator[alternation] !== undefined ? lmnt.locator[alternation].toString().indexOf(alternateNdx) : -1;
                            (indexPos === undefined || ndxPos < indexPos) && -1 !== ndxPos && (bestMatch = lmnt, 
                            indexPos = ndxPos);
                        }), bestMatch ? bestMatch.locator.slice((targetAlternation !== undefined ? targetAlternation : bestMatch.alternation) + 1) : targetAlternation !== undefined ? resolveNdxInitializer(pos, alternateNdx) : undefined;
                    }
                    if (testPos > 1e4) throw "Inputmask: There is probably an error in your mask definition or in the code. Create an issue on github with an example of the mask you are using. " + getMaskSet().mask;
                    if (testPos === pos && match.matches === undefined) return matches.push({
                        match: match,
                        locator: loopNdx.reverse(),
                        cd: cacheDependency
                    }), !0;
                    if (match.matches !== undefined) {
                        if (match.isGroup && quantifierRecurse !== match) {
                            if (match = handleMatch(maskToken.matches[$.inArray(match, maskToken.matches) + 1], loopNdx)) return !0;
                        } else if (match.isOptional) {
                            var optionalToken = match;
                            if (match = resolveTestFromToken(match, ndxInitializer, loopNdx, quantifierRecurse)) {
                                if (latestMatch = matches[matches.length - 1].match, !isFirstMatch(latestMatch, optionalToken)) return !0;
                                insertStop = !0, testPos = pos;
                            }
                        } else if (match.isAlternator) {
                            var maltMatches, alternateToken = match, malternateMatches = [], currentMatches = matches.slice(), loopNdxCnt = loopNdx.length, altIndex = ndxInitializer.length > 0 ? ndxInitializer.shift() : -1;
                            if (-1 === altIndex || "string" == typeof altIndex) {
                                var amndx, currentPos = testPos, ndxInitializerClone = ndxInitializer.slice(), altIndexArr = [];
                                if ("string" == typeof altIndex) altIndexArr = altIndex.split(","); else for (amndx = 0; amndx < alternateToken.matches.length; amndx++) altIndexArr.push(amndx);
                                for (var ndx = 0; ndx < altIndexArr.length; ndx++) {
                                    if (amndx = parseInt(altIndexArr[ndx]), matches = [], ndxInitializer = resolveNdxInitializer(testPos, amndx, loopNdxCnt) || ndxInitializerClone.slice(), 
                                    !0 !== (match = handleMatch(alternateToken.matches[amndx] || maskToken.matches[amndx], [ amndx ].concat(loopNdx), quantifierRecurse) || match) && match !== undefined && altIndexArr[altIndexArr.length - 1] < alternateToken.matches.length) {
                                        var ntndx = $.inArray(match, maskToken.matches) + 1;
                                        maskToken.matches.length > ntndx && (match = handleMatch(maskToken.matches[ntndx], [ ntndx ].concat(loopNdx.slice(1, loopNdx.length)), quantifierRecurse)) && (altIndexArr.push(ntndx.toString()), 
                                        $.each(matches, function(ndx, lmnt) {
                                            lmnt.alternation = loopNdx.length - 1;
                                        }));
                                    }
                                    maltMatches = matches.slice(), testPos = currentPos, matches = [];
                                    for (var ndx1 = 0; ndx1 < maltMatches.length; ndx1++) {
                                        var altMatch = maltMatches[ndx1], dropMatch = !1;
                                        altMatch.alternation = altMatch.alternation || loopNdxCnt;
                                        for (var ndx2 = 0; ndx2 < malternateMatches.length; ndx2++) {
                                            var altMatch2 = malternateMatches[ndx2];
                                            if ("string" != typeof altIndex || -1 !== $.inArray(altMatch.locator[altMatch.alternation].toString(), altIndexArr)) {
                                                if (function(source, target) {
                                                    return source.match.nativeDef === target.match.nativeDef || source.match.def === target.match.nativeDef || source.match.nativeDef === target.match.def;
                                                }(altMatch, altMatch2)) {
                                                    dropMatch = !0, altMatch.alternation === altMatch2.alternation && -1 === altMatch2.locator[altMatch2.alternation].toString().indexOf(altMatch.locator[altMatch.alternation]) && (altMatch2.locator[altMatch2.alternation] = altMatch2.locator[altMatch2.alternation] + "," + altMatch.locator[altMatch.alternation], 
                                                    altMatch2.alternation = altMatch.alternation), altMatch.match.nativeDef === altMatch2.match.def && (altMatch.locator[altMatch.alternation] = altMatch2.locator[altMatch2.alternation], 
                                                    malternateMatches.splice(malternateMatches.indexOf(altMatch2), 1, altMatch));
                                                    break;
                                                }
                                                if (altMatch.match.def === altMatch2.match.def) {
                                                    dropMatch = !1;
                                                    break;
                                                }
                                                if (function(source, target) {
                                                    return null === source.match.fn && null !== target.match.fn && target.match.fn.test(source.match.def, getMaskSet(), pos, !1, opts, !1);
                                                }(altMatch, altMatch2) || function(source, target) {
                                                    return null !== source.match.fn && null !== target.match.fn && target.match.fn.test(source.match.def.replace(/[\[\]]/g, ""), getMaskSet(), pos, !1, opts, !1);
                                                }(altMatch, altMatch2)) {
                                                    altMatch.alternation === altMatch2.alternation && -1 === altMatch.locator[altMatch.alternation].toString().indexOf(altMatch2.locator[altMatch2.alternation].toString().split("")[0]) && (altMatch.na = altMatch.na || altMatch.locator[altMatch.alternation].toString(), 
                                                    -1 === altMatch.na.indexOf(altMatch.locator[altMatch.alternation].toString().split("")[0]) && (altMatch.na = altMatch.na + "," + altMatch.locator[altMatch2.alternation].toString().split("")[0]), 
                                                    dropMatch = !0, altMatch.locator[altMatch.alternation] = altMatch2.locator[altMatch2.alternation].toString().split("")[0] + "," + altMatch.locator[altMatch.alternation], 
                                                    malternateMatches.splice(malternateMatches.indexOf(altMatch2), 0, altMatch));
                                                    break;
                                                }
                                            }
                                        }
                                        dropMatch || malternateMatches.push(altMatch);
                                    }
                                }
                                "string" == typeof altIndex && (malternateMatches = $.map(malternateMatches, function(lmnt, ndx) {
                                    if (isFinite(ndx)) {
                                        var alternation = lmnt.alternation, altLocArr = lmnt.locator[alternation].toString().split(",");
                                        lmnt.locator[alternation] = undefined, lmnt.alternation = undefined;
                                        for (var alndx = 0; alndx < altLocArr.length; alndx++) -1 !== $.inArray(altLocArr[alndx], altIndexArr) && (lmnt.locator[alternation] !== undefined ? (lmnt.locator[alternation] += ",", 
                                        lmnt.locator[alternation] += altLocArr[alndx]) : lmnt.locator[alternation] = parseInt(altLocArr[alndx]), 
                                        lmnt.alternation = alternation);
                                        if (lmnt.locator[alternation] !== undefined) return lmnt;
                                    }
                                })), matches = currentMatches.concat(malternateMatches), testPos = pos, insertStop = matches.length > 0, 
                                match = malternateMatches.length > 0, ndxInitializer = ndxInitializerClone.slice();
                            } else match = handleMatch(alternateToken.matches[altIndex] || maskToken.matches[altIndex], [ altIndex ].concat(loopNdx), quantifierRecurse);
                            if (match) return !0;
                        } else if (match.isQuantifier && quantifierRecurse !== maskToken.matches[$.inArray(match, maskToken.matches) - 1]) for (var qt = match, qndx = ndxInitializer.length > 0 ? ndxInitializer.shift() : 0; qndx < (isNaN(qt.quantifier.max) ? qndx + 1 : qt.quantifier.max) && testPos <= pos; qndx++) {
                            var tokenGroup = maskToken.matches[$.inArray(qt, maskToken.matches) - 1];
                            if (match = handleMatch(tokenGroup, [ qndx ].concat(loopNdx), tokenGroup)) {
                                if (latestMatch = matches[matches.length - 1].match, latestMatch.optionalQuantifier = qndx > qt.quantifier.min - 1, 
                                isFirstMatch(latestMatch, tokenGroup)) {
                                    if (qndx > qt.quantifier.min - 1) {
                                        insertStop = !0, testPos = pos;
                                        break;
                                    }
                                    return !0;
                                }
                                return !0;
                            }
                        } else if (match = resolveTestFromToken(match, ndxInitializer, loopNdx, quantifierRecurse)) return !0;
                    } else testPos++;
                }
                for (var tndx = ndxInitializer.length > 0 ? ndxInitializer.shift() : 0; tndx < maskToken.matches.length; tndx++) if (!0 !== maskToken.matches[tndx].isQuantifier) {
                    var match = handleMatch(maskToken.matches[tndx], [ tndx ].concat(loopNdx), quantifierRecurse);
                    if (match && testPos === pos) return match;
                    if (testPos > pos) break;
                }
            }
            function filterTests(tests) {
                if (opts.keepStatic && pos > 0 && tests.length > 1 + ("" === tests[tests.length - 1].match.def ? 1 : 0) && !0 !== tests[0].match.optionality && !0 !== tests[0].match.optionalQuantifier && null === tests[0].match.fn && !/[0-9a-bA-Z]/.test(tests[0].match.def)) {
                    if (getMaskSet().validPositions[pos - 1] === undefined) return [ determineTestTemplate(tests) ];
                    if (getMaskSet().validPositions[pos - 1].alternation === tests[0].alternation) return [ determineTestTemplate(tests) ];
                    if (getMaskSet().validPositions[pos - 1]) return [ determineTestTemplate(tests) ];
                }
                return tests;
            }
            var latestMatch, maskTokens = getMaskSet().maskToken, testPos = ndxIntlzr ? tstPs : 0, ndxInitializer = ndxIntlzr ? ndxIntlzr.slice() : [ 0 ], matches = [], insertStop = !1, cacheDependency = ndxIntlzr ? ndxIntlzr.join("") : "";
            if (pos > -1) {
                if (ndxIntlzr === undefined) {
                    for (var test, previousPos = pos - 1; (test = getMaskSet().validPositions[previousPos] || getMaskSet().tests[previousPos]) === undefined && previousPos > -1; ) previousPos--;
                    test !== undefined && previousPos > -1 && (ndxInitializer = function(tests) {
                        var locator = [];
                        return $.isArray(tests) || (tests = [ tests ]), tests.length > 0 && (tests[0].alternation === undefined ? 0 === (locator = determineTestTemplate(tests.slice()).locator.slice()).length && (locator = tests[0].locator.slice()) : $.each(tests, function(ndx, tst) {
                            if ("" !== tst.def) if (0 === locator.length) locator = tst.locator.slice(); else for (var i = 0; i < locator.length; i++) tst.locator[i] && -1 === locator[i].toString().indexOf(tst.locator[i]) && (locator[i] += "," + tst.locator[i]);
                        })), locator;
                    }(test), cacheDependency = ndxInitializer.join(""), testPos = previousPos);
                }
                if (getMaskSet().tests[pos] && getMaskSet().tests[pos][0].cd === cacheDependency) return filterTests(getMaskSet().tests[pos]);
                for (var mtndx = ndxInitializer.shift(); mtndx < maskTokens.length && !(resolveTestFromToken(maskTokens[mtndx], ndxInitializer, [ mtndx ]) && testPos === pos || testPos > pos); mtndx++) ;
            }
            return (0 === matches.length || insertStop) && matches.push({
                match: {
                    fn: null,
                    cardinality: 0,
                    optionality: !0,
                    casing: null,
                    def: "",
                    placeholder: ""
                },
                locator: [],
                cd: cacheDependency
            }), ndxIntlzr !== undefined && getMaskSet().tests[pos] ? filterTests($.extend(!0, [], matches)) : (getMaskSet().tests[pos] = $.extend(!0, [], matches), 
            filterTests(getMaskSet().tests[pos]));
        }
        function getBufferTemplate() {
            return getMaskSet()._buffer === undefined && (getMaskSet()._buffer = getMaskTemplate(!1, 1), 
            getMaskSet().buffer === undefined && (getMaskSet().buffer = getMaskSet()._buffer.slice())), 
            getMaskSet()._buffer;
        }
        function getBuffer(noCache) {
            return getMaskSet().buffer !== undefined && !0 !== noCache || (getMaskSet().buffer = getMaskTemplate(!0, getLastValidPosition(), !0)), 
            getMaskSet().buffer;
        }
        function refreshFromBuffer(start, end, buffer) {
            var i, p;
            if (!0 === start) resetMaskSet(), start = 0, end = buffer.length; else for (i = start; i < end; i++) delete getMaskSet().validPositions[i];
            for (p = start, i = start; i < end; i++) if (resetMaskSet(!0), buffer[i] !== opts.skipOptionalPartCharacter) {
                var valResult = isValid(p, buffer[i], !0, !0);
                !1 !== valResult && (resetMaskSet(!0), p = valResult.caret !== undefined ? valResult.caret : valResult.pos + 1);
            }
        }
        function casing(elem, test, pos) {
            switch (opts.casing || test.casing) {
              case "upper":
                elem = elem.toUpperCase();
                break;

              case "lower":
                elem = elem.toLowerCase();
                break;

              case "title":
                var posBefore = getMaskSet().validPositions[pos - 1];
                elem = 0 === pos || posBefore && posBefore.input === String.fromCharCode(Inputmask.keyCode.SPACE) ? elem.toUpperCase() : elem.toLowerCase();
                break;

              default:
                if ($.isFunction(opts.casing)) {
                    var args = Array.prototype.slice.call(arguments);
                    args.push(getMaskSet().validPositions), elem = opts.casing.apply(this, args);
                }
            }
            return elem;
        }
        function checkAlternationMatch(altArr1, altArr2, na) {
            for (var naNdx, altArrC = opts.greedy ? altArr2 : altArr2.slice(0, 1), isMatch = !1, naArr = na !== undefined ? na.split(",") : [], i = 0; i < naArr.length; i++) -1 !== (naNdx = altArr1.indexOf(naArr[i])) && altArr1.splice(naNdx, 1);
            for (var alndx = 0; alndx < altArr1.length; alndx++) if (-1 !== $.inArray(altArr1[alndx], altArrC)) {
                isMatch = !0;
                break;
            }
            return isMatch;
        }
        function isValid(pos, c, strict, fromSetValid, fromAlternate, validateOnly) {
            function isSelection(posObj) {
                var selection = isRTL ? posObj.begin - posObj.end > 1 || posObj.begin - posObj.end == 1 : posObj.end - posObj.begin > 1 || posObj.end - posObj.begin == 1;
                return selection && 0 === posObj.begin && posObj.end === getMaskSet().maskLength ? "full" : selection;
            }
            function _isValid(position, c, strict) {
                var rslt = !1;
                return $.each(getTests(position), function(ndx, tst) {
                    for (var test = tst.match, loopend = c ? 1 : 0, chrs = "", i = test.cardinality; i > loopend; i--) chrs += getBufferElement(position - (i - 1));
                    if (c && (chrs += c), getBuffer(!0), !1 !== (rslt = null != test.fn ? test.fn.test(chrs, getMaskSet(), position, strict, opts, isSelection(pos)) : (c === test.def || c === opts.skipOptionalPartCharacter) && "" !== test.def && {
                        c: getPlaceholder(position, test, !0) || test.def,
                        pos: position
                    })) {
                        var elem = rslt.c !== undefined ? rslt.c : c;
                        elem = elem === opts.skipOptionalPartCharacter && null === test.fn ? getPlaceholder(position, test, !0) || test.def : elem;
                        var validatedPos = position, possibleModifiedBuffer = getBuffer();
                        if (rslt.remove !== undefined && ($.isArray(rslt.remove) || (rslt.remove = [ rslt.remove ]), 
                        $.each(rslt.remove.sort(function(a, b) {
                            return b - a;
                        }), function(ndx, lmnt) {
                            stripValidPositions(lmnt, lmnt + 1, !0);
                        })), rslt.insert !== undefined && ($.isArray(rslt.insert) || (rslt.insert = [ rslt.insert ]), 
                        $.each(rslt.insert.sort(function(a, b) {
                            return a - b;
                        }), function(ndx, lmnt) {
                            isValid(lmnt.pos, lmnt.c, !0, fromSetValid);
                        })), rslt.refreshFromBuffer) {
                            var refresh = rslt.refreshFromBuffer;
                            if (refreshFromBuffer(!0 === refresh ? refresh : refresh.start, refresh.end, possibleModifiedBuffer), 
                            rslt.pos === undefined && rslt.c === undefined) return rslt.pos = getLastValidPosition(), 
                            !1;
                            if ((validatedPos = rslt.pos !== undefined ? rslt.pos : position) !== position) return rslt = $.extend(rslt, isValid(validatedPos, elem, !0, fromSetValid)), 
                            !1;
                        } else if (!0 !== rslt && rslt.pos !== undefined && rslt.pos !== position && (validatedPos = rslt.pos, 
                        refreshFromBuffer(position, validatedPos, getBuffer().slice()), validatedPos !== position)) return rslt = $.extend(rslt, isValid(validatedPos, elem, !0)), 
                        !1;
                        return (!0 === rslt || rslt.pos !== undefined || rslt.c !== undefined) && (ndx > 0 && resetMaskSet(!0), 
                        setValidPosition(validatedPos, $.extend({}, tst, {
                            input: casing(elem, test, validatedPos)
                        }), fromSetValid, isSelection(pos)) || (rslt = !1), !1);
                    }
                }), rslt;
            }
            function setValidPosition(pos, validTest, fromSetValid, isSelection) {
                if (isSelection || opts.insertMode && getMaskSet().validPositions[pos] !== undefined && fromSetValid === undefined) {
                    var i, positionsClone = $.extend(!0, {}, getMaskSet().validPositions), lvp = getLastValidPosition(undefined, !0);
                    for (i = pos; i <= lvp; i++) delete getMaskSet().validPositions[i];
                    getMaskSet().validPositions[pos] = $.extend(!0, {}, validTest);
                    var j, valid = !0, vps = getMaskSet().validPositions, needsValidation = !1, initialLength = getMaskSet().maskLength;
                    for (i = j = pos; i <= lvp; i++) {
                        var t = positionsClone[i];
                        if (t !== undefined) for (var posMatch = j; posMatch < getMaskSet().maskLength && (null === t.match.fn && vps[i] && (!0 === vps[i].match.optionalQuantifier || !0 === vps[i].match.optionality) || null != t.match.fn); ) {
                            if (posMatch++, !1 === needsValidation && positionsClone[posMatch] && positionsClone[posMatch].match.def === t.match.def) getMaskSet().validPositions[posMatch] = $.extend(!0, {}, positionsClone[posMatch]), 
                            getMaskSet().validPositions[posMatch].input = t.input, fillMissingNonMask(posMatch), 
                            j = posMatch, valid = !0; else if (positionCanMatchDefinition(posMatch, t.match.def)) {
                                var result = isValid(posMatch, t.input, !0, !0);
                                valid = !1 !== result, j = result.caret || result.insert ? getLastValidPosition() : posMatch, 
                                needsValidation = !0;
                            } else if (!(valid = !0 === t.generatedInput) && posMatch >= getMaskSet().maskLength - 1) break;
                            if (getMaskSet().maskLength < initialLength && (getMaskSet().maskLength = initialLength), 
                            valid) break;
                        }
                        if (!valid) break;
                    }
                    if (!valid) return getMaskSet().validPositions = $.extend(!0, {}, positionsClone), 
                    resetMaskSet(!0), !1;
                } else getMaskSet().validPositions[pos] = $.extend(!0, {}, validTest);
                return resetMaskSet(!0), !0;
            }
            function fillMissingNonMask(maskPos) {
                for (var pndx = maskPos - 1; pndx > -1 && !getMaskSet().validPositions[pndx]; pndx--) ;
                var testTemplate, testsFromPos;
                for (pndx++; pndx < maskPos; pndx++) getMaskSet().validPositions[pndx] === undefined && (!1 === opts.jitMasking || opts.jitMasking > pndx) && ("" === (testsFromPos = getTests(pndx, getTestTemplate(pndx - 1).locator, pndx - 1).slice())[testsFromPos.length - 1].match.def && testsFromPos.pop(), 
                (testTemplate = determineTestTemplate(testsFromPos)) && (testTemplate.match.def === opts.radixPointDefinitionSymbol || !isMask(pndx, !0) || $.inArray(opts.radixPoint, getBuffer()) < pndx && testTemplate.match.fn && testTemplate.match.fn.test(getPlaceholder(pndx), getMaskSet(), pndx, !1, opts)) && !1 !== (result = _isValid(pndx, getPlaceholder(pndx, testTemplate.match, !0) || (null == testTemplate.match.fn ? testTemplate.match.def : "" !== getPlaceholder(pndx) ? getPlaceholder(pndx) : getBuffer()[pndx]), !0)) && (getMaskSet().validPositions[result.pos || pndx].generatedInput = !0));
            }
            strict = !0 === strict;
            var maskPos = pos;
            pos.begin !== undefined && (maskPos = isRTL && !isSelection(pos) ? pos.end : pos.begin);
            var result = !0, positionsClone = $.extend(!0, {}, getMaskSet().validPositions);
            if ($.isFunction(opts.preValidation) && !strict && !0 !== fromSetValid && !0 !== validateOnly && (result = opts.preValidation(getBuffer(), maskPos, c, isSelection(pos), opts)), 
            !0 === result) {
                if (fillMissingNonMask(maskPos), isSelection(pos) && (handleRemove(undefined, Inputmask.keyCode.DELETE, pos, !0, !0), 
                maskPos = getMaskSet().p), maskPos < getMaskSet().maskLength && (maxLength === undefined || maskPos < maxLength) && (result = _isValid(maskPos, c, strict), 
                (!strict || !0 === fromSetValid) && !1 === result && !0 !== validateOnly)) {
                    var currentPosValid = getMaskSet().validPositions[maskPos];
                    if (!currentPosValid || null !== currentPosValid.match.fn || currentPosValid.match.def !== c && c !== opts.skipOptionalPartCharacter) {
                        if ((opts.insertMode || getMaskSet().validPositions[seekNext(maskPos)] === undefined) && !isMask(maskPos, !0)) for (var nPos = maskPos + 1, snPos = seekNext(maskPos); nPos <= snPos; nPos++) if (!1 !== (result = _isValid(nPos, c, strict))) {
                            !function(originalPos, newPos) {
                                var vp = getMaskSet().validPositions[newPos];
                                if (vp) for (var targetLocator = vp.locator, tll = targetLocator.length, ps = originalPos; ps < newPos; ps++) if (getMaskSet().validPositions[ps] === undefined && !isMask(ps, !0)) {
                                    var tests = getTests(ps).slice(), bestMatch = determineTestTemplate(tests, !0), equality = -1;
                                    "" === tests[tests.length - 1].match.def && tests.pop(), $.each(tests, function(ndx, tst) {
                                        for (var i = 0; i < tll; i++) {
                                            if (tst.locator[i] === undefined || !checkAlternationMatch(tst.locator[i].toString().split(","), targetLocator[i].toString().split(","), tst.na)) {
                                                var targetAI = targetLocator[i], bestMatchAI = bestMatch.locator[i], tstAI = tst.locator[i];
                                                targetAI - bestMatchAI > Math.abs(targetAI - tstAI) && (bestMatch = tst);
                                                break;
                                            }
                                            equality < i && (equality = i, bestMatch = tst);
                                        }
                                    }), (bestMatch = $.extend({}, bestMatch, {
                                        input: getPlaceholder(ps, bestMatch.match, !0) || bestMatch.match.def
                                    })).generatedInput = !0, setValidPosition(ps, bestMatch, !0), getMaskSet().validPositions[newPos] = undefined, 
                                    _isValid(newPos, vp.input, !0);
                                }
                            }(maskPos, result.pos !== undefined ? result.pos : nPos), maskPos = nPos;
                            break;
                        }
                    } else result = {
                        caret: seekNext(maskPos)
                    };
                }
                !1 === result && opts.keepStatic && !strict && !0 !== fromAlternate && (result = function(pos, c, strict) {
                    var lastAlt, alternation, altPos, prevAltPos, i, validPos, altNdxs, decisionPos, validPsClone = $.extend(!0, {}, getMaskSet().validPositions), isValidRslt = !1, lAltPos = getLastValidPosition();
                    for (prevAltPos = getMaskSet().validPositions[lAltPos]; lAltPos >= 0; lAltPos--) if ((altPos = getMaskSet().validPositions[lAltPos]) && altPos.alternation !== undefined) {
                        if (lastAlt = lAltPos, alternation = getMaskSet().validPositions[lastAlt].alternation, 
                        prevAltPos.locator[altPos.alternation] !== altPos.locator[altPos.alternation]) break;
                        prevAltPos = altPos;
                    }
                    if (alternation !== undefined) {
                        decisionPos = parseInt(lastAlt);
                        var decisionTaker = prevAltPos.locator[prevAltPos.alternation || alternation] !== undefined ? prevAltPos.locator[prevAltPos.alternation || alternation] : altNdxs[0];
                        decisionTaker.length > 0 && (decisionTaker = decisionTaker.split(",")[0]);
                        var possibilityPos = getMaskSet().validPositions[decisionPos], prevPos = getMaskSet().validPositions[decisionPos - 1];
                        $.each(getTests(decisionPos, prevPos ? prevPos.locator : undefined, decisionPos - 1), function(ndx, test) {
                            altNdxs = test.locator[alternation] ? test.locator[alternation].toString().split(",") : [];
                            for (var mndx = 0; mndx < altNdxs.length; mndx++) {
                                var validInputs = [], staticInputsBeforePos = 0, staticInputsBeforePosAlternate = 0, verifyValidInput = !1;
                                if (decisionTaker < altNdxs[mndx] && (test.na === undefined || -1 === $.inArray(altNdxs[mndx], test.na.split(",")) || -1 === $.inArray(decisionTaker.toString(), altNdxs))) {
                                    getMaskSet().validPositions[decisionPos] = $.extend(!0, {}, test);
                                    var possibilities = getMaskSet().validPositions[decisionPos].locator;
                                    for (getMaskSet().validPositions[decisionPos].locator[alternation] = parseInt(altNdxs[mndx]), 
                                    null == test.match.fn ? (possibilityPos.input !== test.match.def && (verifyValidInput = !0, 
                                    !0 !== possibilityPos.generatedInput && validInputs.push(possibilityPos.input)), 
                                    staticInputsBeforePosAlternate++, getMaskSet().validPositions[decisionPos].generatedInput = !/[0-9a-bA-Z]/.test(test.match.def), 
                                    getMaskSet().validPositions[decisionPos].input = test.match.def) : getMaskSet().validPositions[decisionPos].input = possibilityPos.input, 
                                    i = decisionPos + 1; i < getLastValidPosition(undefined, !0) + 1; i++) (validPos = getMaskSet().validPositions[i]) && !0 !== validPos.generatedInput && /[0-9a-bA-Z]/.test(validPos.input) ? validInputs.push(validPos.input) : i < pos && staticInputsBeforePos++, 
                                    delete getMaskSet().validPositions[i];
                                    for (verifyValidInput && validInputs[0] === test.match.def && validInputs.shift(), 
                                    resetMaskSet(!0), isValidRslt = !0; validInputs.length > 0; ) {
                                        var input = validInputs.shift();
                                        if (input !== opts.skipOptionalPartCharacter && !(isValidRslt = isValid(getLastValidPosition(undefined, !0) + 1, input, !1, fromSetValid, !0))) break;
                                    }
                                    if (isValidRslt) {
                                        getMaskSet().validPositions[decisionPos].locator = possibilities;
                                        var targetLvp = getLastValidPosition(pos) + 1;
                                        for (i = decisionPos + 1; i < getLastValidPosition() + 1; i++) ((validPos = getMaskSet().validPositions[i]) === undefined || null == validPos.match.fn) && i < pos + (staticInputsBeforePosAlternate - staticInputsBeforePos) && staticInputsBeforePosAlternate++;
                                        isValidRslt = isValid((pos += staticInputsBeforePosAlternate - staticInputsBeforePos) > targetLvp ? targetLvp : pos, c, strict, fromSetValid, !0);
                                    }
                                    if (isValidRslt) return !1;
                                    resetMaskSet(), getMaskSet().validPositions = $.extend(!0, {}, validPsClone);
                                }
                            }
                        });
                    }
                    return isValidRslt;
                }(maskPos, c, strict)), !0 === result && (result = {
                    pos: maskPos
                });
            }
            if ($.isFunction(opts.postValidation) && !1 !== result && !strict && !0 !== fromSetValid && !0 !== validateOnly) {
                var postResult = opts.postValidation(getBuffer(!0), result, opts);
                if (postResult.refreshFromBuffer && postResult.buffer) {
                    var refresh = postResult.refreshFromBuffer;
                    refreshFromBuffer(!0 === refresh ? refresh : refresh.start, refresh.end, postResult.buffer);
                }
                result = !0 === postResult ? result : postResult;
            }
            return result && result.pos === undefined && (result.pos = maskPos), !1 !== result && !0 !== validateOnly || (resetMaskSet(!0), 
            getMaskSet().validPositions = $.extend(!0, {}, positionsClone)), result;
        }
        function isMask(pos, strict) {
            var test = getTestTemplate(pos).match;
            if ("" === test.def && (test = getTest(pos).match), null != test.fn) return test.fn;
            if (!0 !== strict && pos > -1) {
                var tests = getTests(pos);
                return tests.length > 1 + ("" === tests[tests.length - 1].match.def ? 1 : 0);
            }
            return !1;
        }
        function seekNext(pos, newBlock) {
            var maskL = getMaskSet().maskLength;
            if (pos >= maskL) return maskL;
            var position = pos;
            for (getTests(maskL + 1).length > 1 &&>x	ó¡€±ìÜŸçtŞú7ğş÷X¿À¼¿@07zz5Ÿ~}5.­ì¢Lg¢ì•ú$ø3ºğOñ÷…+«z¶Š¼
:œíuf(Ê6DsÌ=FÄÃpa™R‘N¦"hŸRFİbMWt«uAKG±@§kçfæÈS#Ğé›dÂãóKLÚ1a1•rfjuy­ÂûüFØğ\(À+)Dvogº;.ÑA'Sˆ´b·èÇqÄ}€@õ¾C‹UëH‡Ë3*}ÑÛN¬–è:2»m*îçtuıJ‘”IÍ@-ÙÃ…O^ôÕ =`¶±ñ;~—ãC÷iOE=Y‹IsòIçQ1aÛwz¼‡¨Å^ñ–îCéğ\÷¾ Mø(;fõãS{>êœş«[§¾ã.zÎcª÷ÚãWbï >¡¨=ÿªµŒ§bšÑsÏm¸?"¼³Ñ¢×µ ä)"ô¦BªÙ®¾¾cl¥¶™1À¯ú5>‘òÖ/W5!XP‚$QÊj<u'ÉDN É•¼¯]™t×XcMÂÉÌ#“°^}$a|¤»1ŠTê(ì0~r3ß3i!£ïwÇ!±~¡tZR"šŒD^æ%!šá’8¯áøÉÏ¯è…°ämÁBbâc¤Tqğò%áİ©'µ²>DºÌ‚¢©…#`„3éwÒJ(-’c‡â˜%×=¬âv{WY€‘åYV»ÃA…áğz—Æ8óI4“ˆ·ÆChAlÉİ!v4\À-õÑÒ5[›ß±NÃ³á(4|úqÜ‚¡+&kë÷&*1šãúÓ$ğPG‡Xw	 1 °#âØ/öÃ*ù1$S¢]0@¦	ø(â|Iø;ü^‡Mõ=ıšº¾Ñ|Uu±Œ™GxQpêg„l¦Ç( 'ÏÇèqlıÔ>£2Ò“˜Û&R!?·dÕnŞSğM(½á ÷â,øXÍTÌ™
Tpk¨úü	 µhfÖ‘ıWŒPÀvà+ºD¡¥ö*Ì{èÜÒ‰ûYÇ-Y£Ü‰¾	˜sßu¡Ëêa e,øûw!`ˆhxk¦§w¢ÜÊNÏ‡ß¦Ü*[`Pß¿¹\èVö¢½ä”ñêĞ§2¨¥µ%0x’®½î»ÔÓæc:ú±¦zÚm­po—Õ´VDF•3ÈbÄMôrÜS^1.Œõ¾TÛYG;©)„Tc.–€·ry4–#)BEìŠ¶Âub8ÕÙ£ ˆÿÃsË¼×=’pd¸c•ƒ~ «Î014Æ2él<Sb˜aN6|Ç>ê6K¬Ã¾OÓZI–(Î}ö+è_XVw¯ 8Í®!èuuq
ky¡{¶Sqe\ Ø%‚céşH4 HwD<=¦rzŠjÆVÃ‚"ÆÉG¸û¹avÂ¸¬`è‡$nAÄ˜ğ4&BQ°¦Á‡„£V¸­‘çÀeg<IDö×FJšÇ:ÿ#zP©Ÿ¬ßˆq¾¢ÀTìÌ4~=iêQş \û@ªh$D3D•0*Ì:êÈØe6b'û”‰•4T*tšæHeM"¬j\ÂĞ24¬«×1¦ö;EOb,9Ôº¥Ï­Á,êô¸“àèx¿`Áõ]Î½êqàí$ÂL²·Z°ËN\ÎÀG{ŠËfÌÔLƒ%Q–¸û…ÜO†'ĞH¾€=Êğ0i˜Ôç×B+N‰
T"«èSz†‘ÏÅ‰j¤”C‚v'>5ÕNµ~D¦MÄ{1zğ}†BW]:A–|¦‡ ŠrÜNÓ_”Ó+1ÓãJ–UÀÚ»R½x§²QÍ7øR¾|kŒñ<KïñÜ	ƒ²ğ†0»½Õª´&z©Nãô˜T;E~×|0j øÁSˆƒ	('ºX1OíÉgP2­¶#ÿáY[Ø˜ˆYÄÖ{;ñ Y·½çD˜*[¥0Ö¨}P'G+¾~”>èÙëk&KàHñqÿö‘íÌí2‡SÍx*Ô“3HÅèÂ*Ì³ÉDïRm›jºbhHDe„0ô5Šå´iå´E/}eêÚÔ	CÚ1îì[£KAxñÈôˆ™ªô;Â  % /ÖåÁ1ÃÎµ„÷ pã¢ÿÈ…êÓ"LOd?œèµdÿ^ŒŞvo~nğÎ€ìn\T’0Ì7¸,Ÿå/>Çë4¶İg$lÚ¤èèümµ>ô{5gÎ2ûB|Ôr¡³{O<ı.<è”	Î?â@,¬Çºª¾qâŒÁgÂá¸wvãi½(00 ·»ÃÌã©C~pÓ§R\<ŞÓÌœÅÓÂñ¥§—òò‘o§w$×øqœ“¿.Â’8$¯ÙƒF8#ZëQÎZÿé\ú}»ç½ª¯[¡|{İç<£ºPt$ûòÅµ•T6ÈfnQ2Hóå	¤PÎr]›f„ÍüÁ/$p8Ø”õ<'
’0-´.£ç|Ü’XËFRûsx'q2§ p;¬¯éÛ•ãÀ‹lGyyÄ´œ–(Gği*pÂA]ø³"DËO£‰‘Ùi±Ü`â|ûjˆlÔ%ùBÉX¶ˆ DË’”Ó ß¿Ãú
ÎìÏ[;÷Çwrcô]ÇFèd_¤$ ø´ˆ‰}œ¡Ç™|0<Ğ#²ºzô<)¬§Eºh!$a¼c@sè62mÂ²®.›A÷X“Ígr0×ØYı5ã™xÆŒõETÛò¢‰ªYıGŒ‡cFôeÑ`Œ5@F0îŒ“¥ŠlïXM8¬–İÏˆ%5{Û™_÷¿ù¬êLáŒí¥å¯ a‡–[±ı~*Ô}ÔhôŸ·elêÃwÓ/0Å…&§¦~Ü·¬
Û*ïY÷/m/|ğJ\4<‘Ï;Bñ$ôÉšÆµÇÂw]ä´]EîïmÎ¨gIS¹EVœ™oMùîı·ãÆÖûîê‹Ó®èšC5Fr%àÊ§Ä}ğã=#¾àSŸ¹®è¡¿j§ú“:¸— ğl{[&'_3´ıúhA­@™b§dŸÄ´Œï;óäÚıéûüşûì—%ññe×‰„¨›{_yÆJkvıN¬rx÷’_Ì×V^ãmIµÎ
»÷Î*“¾ìêƒ[pu7–s®QeQöV.t¬Vl>/%rU@òn™`×zˆİÙ|8~ä¡ÕµG‰å½ñGî-W’ÉºÖe d©#ˆ"õÌek²Tö~ÖÕîôÄ>œ`ô  G¦F3d7¦ItİÆ¤ñ~fßhÉd¿s×èEÄ[ø hg^Ìétæ³’ä'J9Ši‚ÕëĞ€]-¯^°”uÁêVf˜H øÓ~Q`¿3VIåP¢É¬ª@J×r Š­$wƒı76šÆ¾ Ò{]ô|BŒ F(N'Ş¥WÄº`Œ{N¨Ë,•@`¬+–‘Ëc%8wix%F›¸„Ü%ı†ºM R‚ ÏT
n~!ÄX'¨[¢Œv4‹/¬rö?Ø¿§ĞçüXÃu0g#êŞQbšídæ±R!À’0é%…«$tåêC'FHÉó4ğÌCÁ×§û˜z|Xß5<°Â4İ¶3¦Ë#£bE\Ë1Gæv“"
P†„˜—oæá€f¼†©iD9‘ÍÛÑÙEYXèAe#;Tä %µ¨¾{ŞD§Tv/óó6öÊÍ#³zÒdÌ0¦N^pNKş¾vgGcC1E$ö¥`õc,Ü/ wuÌ_
¦ àÒ×¥h2à‚¨Rî Š¿ “W? •í¥¶â«~ŒœÇÊ
Ü?†~ëªÄ×ô&\	)H$'oWóßÖÍ^8Í«Ã{NÚ^–‡†Œ^2À²1Ï[4ë¡~vošN@l{.û³&Á‰—4i51ñ.»,:"Œ¬!ğioG± N ³(
˜‰óäĞ‘½òkaYSçîÄˆ±Ô)Y¹¤1'ÛwíŠµæÎ¼%0Œ½ï<¾<ÙiŒ~S£={",C©3LIÁÉ%Qö¡
Í«Í ¦¥&EI]cÏ@³Í	„Œ±h´ZNŒLBÅ:p4Xš÷ì)“¯Ëıë°6y d¢’‹°ÈÆÑÓ8¹ë{0ÚµtÍùDHı)¬­ÇŸ³ÃûÈœÏÒà1ïs­
¤•Ñ	Ê‹­F"ƒ®®¥v¾³`â^;…øzéÅÜávaİDâ8rÅ«jÎâEO«µáË9s‹p")¾ \ö#ôN}z€z1Ëd»$9x3–Ş´Ë1WXñàÔ¤ï:îrt|ìJ®wqYèöÚË	-‡0@¯Ãô1¸¢`Mğû‘ ·i¯'å³v¶i-äQiJÄ©èÂkã97H½ÇÄÉ‚ múBµ¤7æiôŒY.gUµœ3 A$…Œœ—„…uÛ;JÔÌm¯çS&üÆÑÇËeé\pBÌŠ¹ÅÔîaîfB3~ıê1œÇ¦[wÀÀ%0rgìÓv ×j†¤YF¥ÔåŒØ¡Õ7×ßÃ:øœíÇ|U·{´°±­ˆî¬v›—-9&ÙØÄ’Œ@v˜¶k§?Î‰	$Åìë3ğmÊSóèµï èkÕĞåªav›>÷ıTW¸`¹ÇP ô<öF\¤Ì”ğÙ~„5:çZ½0áA<äã¨Åo±*Wbi|œå¾>¹nÅÈÆäZJŠá•3¤Wğ©ƒÕU%c„òfõª¨§×ÔÉp$¢Ô²[ÓƒÓpT˜Ï9Í´£•‡{—B®Ù ·(ñ³ka`‚Á¡6}@£ÏâÙRğ¹1ŸÆÆjmL#®Cb-ÖÔ­ëb.”/;ËF	åÆZQN  ØIŒc 9h,
[-=7cdUÜ(›h7aÄ±»H›½@\!¶|“ Ñ|DÆÇÇz	\âagæ##$i‘9iÚä^sä´dWgè„ÂâÔ…‚äô}oó¸¢/œ
_¸fšS¡–7ıTC*İ<ä¹t‰)üí…XT½.&;©E#¹ßB×EØ$!Ä{ºg„ÁóÌ+\0DY'JÇ¬ûìß:v/pw¸¯p$½
½wÕß‹µàâ:ÍBA³jAAA_¤:MÑ@@²ÂA@@´¬iT+®¨$z|@ İLî·H“5 C»ÃêİußÆ3q³Ü^ÇÈ’…
\õ˜€È¥«zT­¦üÃLiaXˆ*Z¨ pÚ–Î*¨‘$Lµ%õ%.Û›'qºÒ˜á¦1Õ£Æ©å_™+lo“ÃkyPxQy v¼,fŞÜ	csd*‡m²t.<û• Y¾8÷&]´‚5.p…kÜ®yB¦EóN?¥yê?æÅì©†õnQìnY¤µ–è‚|Ê4Ü‹–J5ÌÎÜÄO“M	İdxbtı„fm—“î…X´AF 6(’Ù¢Å`@¯t9s!Ö<ş¹Á»¾”çtÙÒıŸqé^Kò¹ÿq8Å¬Yì­˜û´Éôì…=ÅQ³hË–Ï¦¾ÔÀA+3@LÓ ğ=¡^ø°2ó˜c¤ónV!Í\p‡q1h/D¡øf¶k°
ÜÅNÒG!l‰' ÄñùïL°Á%•Î’F+Hk^”ô~x+0Œu\ÅHàÚ¼ŸİŠAËÚŞæp´í™áv$F`:GgX–·³ßÚ‚`¤„Rn>(ªú]u!cœˆvG„ !@ 0ú¼)ªŠİEÅ„Iš>É®’:ZvPM$İØ¡0xú+ÕÈÇmcy°Å‰ïDŞÕLß\ËÈîjı…ÁV«K'ÕŒöø†YïB[<Œ	£á…ËÔ]ë.aWô;z´Û8” ‰v’¶¥gî U°C®ÁÇ>ÑÂ¢Ë¶÷ÌŠÑõ0	b¯»gc¨¬k,<öv–‘P=ùxÚËlØ“Ó¢‹x
v‰&í)«yûÖ‘Óæ1Ş£ÉùŸ9O‡å!^ ò7¹C–Ì‡Šÿ–jvZ¥¬u¤si»õ0}°çÎÌ]f&­b^ÛºŒĞ&	µã³	Éjà© 2i 1)/·YÛD*-Ttô¸TĞ\;ŒÑ×»ÜÈ=0è´˜\`sF‚@[Ú^ \Äsêé72£Ø Ü’‚õV¹Ô~w—Ú3Nõäµ4æœZCx~BskÃëUKó	/Çì8õØ¤íÎ—¿Â#® “¬3FÎ¹¦;$­Z·z4h›	ÀÈÖ?œ˜ª‘)mWc|#$)b9*`hŒLÖ¸QÏ™WÌãê¸^JReUMÈ#`7°ÑlH Dˆ0,5˜05Œ ÎmİœkNö@-®»lı°²77¶Û|S] q?ğpÃÄ‡é=–š‘…±ÿ±íÅ¨Æ…Á
ÆÖF–—/ÂVì"½;’™œ?8 V•„R¬P»ÄîâôÌÎ¥¡oR˜”n*ÕpY ŒÆ6UèIş†7Sˆ^7µ¹*Í›ô<G¯CAVAæ›X<€Ş™7á <0•7dXû¾#|Sºf§*m'Ôdq ‡Æ¡Zƒ°eÑ_TßÀ†è<§Z˜8Ô~.,\ÃÑ5{¼ÇHBå§btaäÙA"é»T¢Ùßî[se>«ÖùUã­ED®|¤äğ´{ÜP õ¶ãqã/z½JéŠwEİæ“~Ëzb£ImV ı»¨L¿r”Í$å®dÃ'ò~g¨Ç9mz~SÛZõVğ˜Zá6´P ÷!­¤âÊ‚²/läzå‡ÏA\€Ò‰ÅäBœ—çe—å4<ÆcùÜ‚Ó˜Ï™/¼†{ûÎ3.ZÙâ\£T«xÛ8´¸eI8ì.S±¼¿ˆ`C[*	sEF-í4‹Ô½Âb#ÁD(Â
Ãlß ùxDèŞ1J4TîF|"_
Ño¹|ªÅÄ5UàÖHŠG[ésh1×i#v¥ékÇ‰¥éõOm³Q¿šnøZiŒ–i°N2pœŒÎÚˆØS˜ËøÌ|FXØ€GbÖ•;Ã;ØÙoGÍŸ‘—•%o$qºÛÆüúuLÆnéM¬XÖ`Øo½¯îÛÖ*âó}£Z b›I“Å˜ã¼lmâä·7Fš¬G…ìÍÌ$×qâ¤í{’ûY˜–Ë-³NÍÒL€‚à¸0ŞµĞ$ÖœèôÃ G}–ÖŸcû–Ç\TÛB¡H!ÉLG{`•$L8’HìH)XusÌÿ3î=ôÈ_à®ş>wÅH.ˆË;èeÀîVæ$ÈÜ®¢Âó'(§Fæ~Ê·í“G¯^_“kñ†–Ü: tß3Èâ©X\Ì=c
àseÍcK/1tâ'šú*¼Œ2áAñ'CbRĞ˜Üï,nä†Í°u£°ºV­“’9 ¢#</ÈuQLx–TGV.È$q7FÎÄ†»“¯ryª£ŸÚ»DiÆËC *bŒ-¢ÁG è¡PL*Å¹ãø$wºS UÚè ~5h’%ƒ1“Õ¾È€‡w¤¼ã¢§d»s®º!]'nNfbí¯í’u	â—æÖZÉ)‰@”–€`¼e÷ª‚­¿F“‘¢÷PéîÒM—’§÷}ŞŒR=ÎYŞ¸PtÆó/ÈX>œ˜ªÔ´56”A¸]×i¿dŸĞ47YÍ1uµÄÓÚßM‚eş2^»=ªìÅ€6«uŞdG•+™£Â‡>º(	'ĞaÊ®\l¿€<^€fr÷¤µëãoÍíƒÊÛF,ÔŸoÂSÙb}Ğ´ÚÆĞÂæv2©~@ÌÃ}‘JQÓÆŸ qôfVêÈ?*Ëwá”m'¢E»_‹T&GE#L¹êªœ&İ„`+Eˆs4(4ÉPòµ~æ±{ÄG­x>ÃÀ’ÅeÄÜ>A,÷¹·¦Q„“q„zğ*ª,ß×Ow%m|Lå}_ÉñÂO_Á0ÛÛ…Q˜ÒŒÅ0dq ä2¶•³£µC:RÇõĞ#ˆ„“8$€Ø^T-U¨—¼š‰‰ú•s3ˆĞÑûIò@3&êS¿&Ü
ñÁÆ°ÛŒ	·˜‡A›Xš-«V§x¶‡!` E8[§›îÒæÀ®=#t<-9ˆœ«i:Ğ°H÷R9{~(1s¥Ai´ˆ^'Ô_+‹õúXö/şBïN?ß`-.ì¢÷>@Šw«
Íês}Î¥»®& ¨]‰‰øÒ0“+i²a'p+Áõœ³f/@¦n2Kxw˜hb­“‚ì"óHH¹;È*ûhÊe]”hî`Û›7ùš›Ù(¦;­wHûY{ö·H‰nÓ„“·éqV?e˜¬ôc†îèõ$^È;İ*FŠ¸ÌGsZ`Ìï5{>`~ˆ]8g9Ò.UªÖ
¥sØGu:²iPfY’¹, GS·S,³! Å“œ$Q4jâÂí|Trmè¼ûªß<…™¸5ÉäÁ…wÆÆø£óøTIBî/µ„ú”.E%.ÜK¤ŒE‡ÖHw¿‹ÀÁÜá‹5ÀN?ğ!8“Áwãi¹íÁj„Âöiqòˆ‚oÄ¶»M#4ùÄ<ˆÔŠcıhjÿÂÂmc<FGîÛFn—í¢H Ê­*o•ãÇ[Ãƒ^ßÉT—êÀ«F•¤ß…(4FïüVö—Mç_€}Cä^k¯ƒüXh²r¸F¥¨W^¼\áŒWé”ªÖ¨ÊÂfA-A®–ÃËä·ldÄS³xDæ’¿øwa_ÂVÀ«ÜéÕ S%`7T­k˜zåöñPn°"³·é˜óàÿ9ÄHUJ Ğ[Â ğ>üê„r£sï¶â¥ >Æ²"p	&î	 =9Š á™PP¢çbB„r=u{“'×•@á¡©räŞSr³IÏØÑ‰ TéÂşG8U‡“5ŒE“¦@¿ ˜y,À<¨…Z¬ãibk”2$"u—‘Ad±ªİºÂ|´ºfÜ2Î>8fí•óˆWb­ :ÕNµWÛz²'®Ã	H¢ÏP°3›”Vˆ±.şôÀÚ&~!#¬ÌÇ—ß0!–!Jj-gz#2ËR¬¸*ô®Yı2»øN¾ÕŸ2ÆhZsØÔâó‚§Sí.+ú3ièÔ+wASê»$C&3‰—î‡³€U*‡·ùŠ±3	Œş°ñ
ò×Ë·+0‹§©»9N8À†UĞŒoàş._ûÔåNÕ…ëã~ÑqĞ¢Õ2}îV2P¼t|}.6šIÈ2êeşb Ö—exrğ]!q…œu{¶:Œâ$¹©ö ö2Eõb9¢ò+Úƒ„¤ùÛ$‘‹wYDòF¦]ÊoæNhÔêîÍêgH~‡0&¹<,NµSíAì~ª>Aª×Å>¢®Nt¢­´Èh’‡dÆàœd‚~F©Ìsƒ…T_„íò¹ÙliüÚäÕKèâe"CPÛeµa#'wİ7QñHš[T„ÚÇ#HZÛSuÇa‰.Çt¼kë »pâ¹PôêXØÂ—*k„jîÆ?Œ]Œ¨Ë[î6–®\íºª½@µÊzÓ`Ó B³a}Ş?½¼µŒéQ?D©ÀŸŞÃêµCÉJHéD”¡×)á
|Å!uA#2VâV«Â\Á¡ç|Ó{’R‡Tze*…­ÒYé*µµ!Eê}¸,çb~ÉLw´qâØğcêŒ‡¨‹_J[rœ¾â"sÅÓ8ÁI2rÜÁ6ğuÑÌFŸ(€0%ßèÑb&Í{pc›šÒ¶aãıƒä(˜ÏêÌfXXñ.v³İhß‰pŠBtl õâX©-+†ÖÈ¤•ªÀµ&³VÂIé°çU8õ8–{•ínã£qd‰0*G{jÿÎ}¡÷‰)½ı-áç¢{ê5Şjİ™ôèH¦‚tdÛ
iUI_ï–úWvÃñÁêÌíI×íìÏß'H¢±¾•(Ìqı” `F±ŒŒOUd¦1˜}ª@ìLüîp×^«Õö?ÆJ±Ó*Æ’QäÊ¹»¥k[‚73îÀ…K×åŞj·c£¾'Œ0>®#7 tF¨•"RIì¤óÜÊ”l²p½ú¨>FÄ Ã¾D=7È@vF’$“	"î¢šu"XÖ½``g"kÛ¹¾’šâÒgá„ï@R]	™ÈI	
¾­.$¶t“ê5Js"Ì„}ö;ÖV˜lÇÅs·üî;~.Ş!q#cãkñZû¦}vM<·¡wëÅÏ1Béœ5 xÔPÛ ¦ì¼ş–~`R1-˜İtqÒië$µDsTê¥ìš-$™(²ónÛ¬¸ã8kî×rw8—„¸Œ€šÃã°0°%\åIÀ5@•ë@’1	J¥RÓy rƒ&Mô"Q—õyRên(CÉG !Nü1ÙØÄeYºÕQ´ËÃ›øÜÌzİûÅtÅ…ªøçm]?Ú“'æ¹Lœ-ãCƒ&iĞ¯ÊÑ­íºM¶W•·†U'R%U)Ç*7.FxÊÓ<šø= WÁ_õ®ùKÿgSàÏ¯²æßqA/ .|øyÄ3;Ñ,¦¶Y<ùèq†ZP“5³1TÏPËôD®hTš_“WÉÏpI™›Äîë‡…£A˜î8¤Rg¤èb•Œ©vªıµµˆë©[\™‚ˆŒ2É"Y$,ÔÂ:ÈæíÛ·Éñ›ıKköAuµf‚uœ:1ÕŠp"«UÇAÚ»k¤‚!ÕÀáQ‡«,KÿÉªìö¤D‰2ë”±
ÉT{ÈÖ¸?"5©jª©¢°XÓµí‹¿ 5ZòF/îÛÅ×‹¶ GtxyxÕ™0r7V0Ôx#~1>Ğ˜X¯Ûp%6LKí8¤KÔ2¾ÖçcÂÁyğ!ˆ¿¶UÖ} Ú=¼`ÄZpŞxnĞÆ÷¥4=ok}ÇA.
{¢ôˆ.=U?$w/Q€jt‚ŒTCR¥Ék›<¢–v8ö¹+â’ÁTÊÅ}Çÿ.t…E­Ê1ÿê—·¯Ù hGg¬égÖ½ï#Á#a­BèÑaØßIš¤ûãÃ(Q41’4I“©J]È#8¶ño‡ëÖùŒË»¹K~–|'ùFèUÅË°xõ&ç!“t>apä/1ïIÌŒy9w‰‘/3Fşl°ù©3¾*eıpƒ›Ñ0Û°«°iã[[é7,“£e·Q<ùZ/VJ¸pk9Rà<IàAÃİF¼#ÀŒäz˜¬Êê¡¼ü*_[¡}¤4Ä+¼¦RˆßNAWÂpê0»iÅ3ÁMI¨D0—v¥\á,^ó‘“ìêáè£H»IêK?e:Ğ3"J¾á+ÅI.É€"–êtìÓ‹}=ï®ÕŠÄ69pïí©jíIšŸ8€81Î3£ú‹¤7²„ŒÎ óÃ´e¯zííãØ¡ZE0`˜_aĞÑ>B¦¸Z°÷[ÇiIßùÂÄcÂñæ\`fµ7«·ÌVÜ…ëh,'7ÎÄ˜ WÓ)«DIµ¨H×¥ëMHÁlœ7L¦Ed¸‰\uÍtp™y™‚Èİ`9	Í
pœñuªe‚7·hšdtÃ”u~ö='éƒji¦f]ór„< ,iEV¼T)o¥¸:ß¯·øZ“ìÿWÂVäÄZCY¯²¦€5¹¶*É~åÑ Hƒ
¿™èDNö„mJj¾µg§÷MƒæÕå S«˜{€ÅwşhjuõÍ´ŠŠDO|$í½uoÅ;·9KF0`ğùF40”î6:íçLwRGÕU»+% èHÂ¸ :µ6}ÔYó¸Á=Iø‹›Pkcşâk	³µ°º'2"xŞ<Š©aIuÎÎ2£€Î’ûÅËŒ
°Ä²³ÔŸğoÅÏ·Ö—ú^<Çwõ€Ãcş1Xë½f TÔ7aÍI¦VWdîq‹Mµİ,¥í¨<¢P—‚ÄŸMà%ğ °ä.„üáH3æEV9—o¹áĞşˆĞŠR>#'î§êù*Ä¨Í¨Àµ7GK²{ÅÈæ_ğ$b”ÃœK@¥Dİ¤KÈÎ ¼ã
aB&o‰f ±j0©l6¾ï¬Å8‡èm^o?Ó‡À{¢oÄ‘]6”ús‹ö…4aª•Å8"6ÚI
5Å°„xË²Å~GôDvœ‹ÑÍ¦Óš@@R§»¡ĞxhÅ$ÈæuyÓáÒ€‚‹‚¢îánbÔN-`î€xjsõM-®¾™Z\}ãÂP—Ği:‚Ø·À »ÉÅ÷„«5è.ˆ©Åe¦ÃFæòı
óK‡ô'*­İU€¶q‰ÒSmycb /²sšä#¢ÃûüŸ2İY~ñÃvF}©‚::G-®¬AïM$ú>©Æ¸¾è¶r˜ÌZ —EÈæQ‰„P0UU.ºRy‹Ìx iKõDUv@2YLB°©´ı¾­Ö÷V‘®@ŒdAjµÓ÷°¥êóöœ:ÃÒ	…©İ‹SãŠ) ·=Ä4IÀƒ\/RÚ´C±h³8ö¨X@ãPæKàüÇiñgš×7|„ßq¾Oûº»ÒÏŞ9]“3a?W¾W¯ˆÃŠášuÅù×¯š-Mév“âøtœÂì¨‚q;†ÿ~}
K‚!…mŒdäİ@³™o3E_p¹ngWx=y¶r³K`tupµ2Dh…qF¿î+ÜE Gh9ü¾ŠsùÁ„€•Ñù¢	UĞŠâÜĞ„(Ú	ˆĞÕÿu ÎkXı¼²–® 	óC%g†úÖú”qÕ¡ÂSy¢Øu‰/íŠt˜H£a…ï€Ï ’Tø¸*r•ó ²âû $¾¦¤€0,+?Âë˜¨l¾ŒL9e¹P«— ‹ıZÇ„—ÛiÀ	b,hI×…jeI‘“Lÿ3Òl¸,ÿ~¦LŞÖ\Ón•ÆÏ0­:V±×3n¢A‘¹üæ¥^Is!,
áíÖD…õéö³Z{I‡Û Z!ˆÉY¿ÍiĞïM#~åõá~Îí~Ø0çUJ—Ü<¥œ³½›$<ÕŠsòê×¦Ú;@øÉAËq›Æ‚¶Sş˜ºÂ\¡”«Şn&æüé4IŒ¬5µş“‘ÕÊu¤øï,R‡7XµğÔÜºßğTA£ÍCaá:å?;Ó^´.Ö¹«ÿÍ³ +ÇÔ@¹ê½%¯5î®¼7eá^­œW
?Ğ `) TÀÄ(¤ı/…¡öB%pÍÇfÓĞÅDô@$ÃU™ ’¶ñ!HÀçfÑ`*ÍhøDyhF¤	™ 0F)E ô$¥¿ –Štv6cĞ>	×üÎÍaÛ«L2]!T 5/íVãø×bŸ!ƒ-¦¦ÁÖ®3Iƒ”Tk$gÒòSuyò¬ËÂĞaıPa ×r^„ši V<¨›‚¹%†!Q®|2«4æªÑãk¥Pí¼*QÍ‹¢»V€ÎD‚ú…;/ˆz¯’ğ†–’¹BLßÏ±‡ !b‚¶6.`ò_„<E#¼ìøXÏ(¤œŠuIÜÆ6s"\˜…Pƒ®ds»‰½.Õ{m!×7Ôçşt$újƒJoîl°]ÿXÇ¶›ÇÂ2w©ä
ÇHßÀU™è0åÛGa›XŞÙúÀ­Zée4_\ì/ìeæüx#u8Û\F7+îyaL6g8MÅïõj!AèıÂ6_mO9cc"Ğ|š×B+Ÿ+!‘7¬ü@âØ¹şğùÓâÀ~üb8’ó+ş:.‘ß´{İ Ü	5^œxI6É&“§@±J/Ôùœ
[^„F›(Ò"iv9ì¤›Š¨Uy§öC×@H—^¯z©aÕ•y¹VÔëíÍöOÂ",Ú—z,´÷ş+‚h)rnc÷iRAFaµLÇ89Gì©¯c7XáU”´Í-ê³¤É6‘A¢œZäÿƒ‹ìã3Äßs\Å°#Ü$3EÅÜX,Şjë¥ÇÖØí€#.8Wâ–%4ñ(ª½¼T7õ±ë}mék·lõe÷ãrlZ}ëê n|x¥˜Å!Æ‰1%¤1ßR‰2%Û(3¶Û+ÇÇâb\sG…
ºx’¡uãØ”» â+nn®g¦®pAí®¥9BBŒĞ^¶÷|Ç
³®qGK™/+ÇhÖ£À ­¬W¸³-Œ¨ñÄ8ÚÄçTÅ‰‹Æ¨_¶3-®“`®øëí®«a«5yAÑõ¹¢ ÃBõ<ò²jP[Vïçí ±Ïû®{Ÿ:–±O}¬¶#©İnB¶ó2ó³thŠííJvM;CÇ<QÄ8¤`–¬k.µcÕŒtj—ÿÿ°øƒvç?ß q}Dİ¾ä‰¤“H<‹Eò-nå¯ Íñ×ILv’9Éúò"Dö¤_Ğ˜©Gx?¥Q˜QıïÖÿsÌŸ4L>LôÏQ{äpÉã’	t®dôQíqù“ËhóaôNôÎ¾°?6:„¼)Ş…_wo;]­¤ÀÑŸó´&÷9[–Ä ƒ[Xı†K§y@Ş|S` ~Â4¡<’©øVwcı|’8[Ç( €yVÜêWyUñ<cú)ÿ3e“L?¹>ìí¼ÊK…©/£qÂmIº-á¶-ìåÁ:ÿF›Pta¥¯É]òF®éuèÉ3lŒÊĞòäåO¾ÓüòG¯±»Añ\ğİ’¼2 8¸äMx•”ÇIÛÓT“\%k©µAz_+ke7Q¶„$$%{œt Ñü*¯ò}6¨ï@@ç{«³ûíû	 Ğ‹óïa¼#¯¹dG	A§-¯†ÕixñÅDbBQfåV>åü5Õvò•z±®ÇóŸuSú¡ê=Ñ\§¨$øÎÛ®Ïï.)l7à¿ÔÓ¢‚mğÈÃ}m>ï İ   ²%šG×QşkSYäÈ‘Óì›ïÇF£Ç‹¥ôËŸ_•jœÜ&W«õUjı0V>±ƒGÏ0‰›»Îç«’ï—)¸Ş5Ï(Èúpñó ãñŸ§:[ÉWt®ƒ¸;øµÈƒMç[›¶Vÿ¿zg9±Y<ç¢0AAë[{oÂÓí@^/ûı.O¸äİ ócp¤ĞÔ©à ¤+”È¯É­¼¢Ê À ¼q¯­<Î£xv65ñFQuíQL~BC¹b›Âlr=¢u£•¾&Wrız$K\EÅ¨0;Íòğarš™0´:ËC5Ä|;ô`J[ÃÄ&6±Òr=éâ‚¬ò Í|…D¡ÉÃ ¦aéFÿ?ÛS;ï{ŸùòŒ~ò¸üÃ¿–Yâ‹\ŠnÔ‘KîRÄÏ6}é6	Rê`–JéÂlJ¼9˜‘Üu¯Š³íjÔÎ^ÙzÊš„€(âAB	M((èùÀ¿†~ŒRKò¨ßú è¨ÿƒ0ğíoİ:	8üò‰ìÂƒ+\/6ì]|şP¾!U›«vºãÄ[y ?ßû;yGEĞÌØç•³[v}=‚UĞ²S ¨Ly	t+TI•TI…JV˜GWÌ£vú”™_* ¶d«ëFõ‡}Ü-îM88x$¨ßtıˆ ×ÅA@d÷C°æ"ô2zñWaoy=á‰ê·ı<c\/"*Ìà…¸Ëbˆ3µhB?™¢¶B7E&p½hìÊÊVYŸTdƒ”P¬ãoû¬ 	–H,Ä[ÈäÊ§÷\­-æwT_|éô¨ål€Á9áË	©ü5÷˜İùıFş£Fà­€O7"eìş}Ã¶¸ö¬åg Å¿ïr¿B÷ìO¿iã°YtXÁ—"-¥”2.Y²J,JŞÌM Q¤IaÂA%ƒB%”…"íOÆŒD#¿M±i-	F"‘ˆ b·‚G’()©%^8£¯s”I3$¹¨< ßê´Ó%É)—16CŠ›wÀHæ3
"?Œ±ÀB!ñ%¨­¢,ÕMöò SıôV—ƒ0åkÖ$²–1+“´c\[UBgÈœQpÀ…K¦k!³‡@AÇ”Ëš˜}ñŸ>ÛÉ;(Ÿ¿í@1$…‚¼
Ü¯½í½baVJh÷øcz!©ŸÊ¾&…iJ‰ÁÇ  `¯«ûü­¥ÌE7xˆÓÊn÷)*¤íTÙÓ¬°¥BúªÅĞÕãÚÌó?ôòh;ÅõH¬¼}´óæñü<‘­¤9øA¢™•7$[	D@^O7Æ¹Yv‘Ÿ‡XxƒyfâÈ;U„Yà¥ûlJR©¡GçHì:EâŠnyÇ½„ä–(­æ<B"	Œˆ<c²ÌHÃJÁª˜İÂ–46ÙÔ¢3ŠD±‘ˆ‰˜È=C™&ğÛaï!€ÙIÄL}.iD	Ş,=´P!DîSHgA–bà$:µš£]nF0å÷h¸R$²¾~qáçoİŠÌS¢è	 såEV´/`”©Ø=€ ˆ™È)ÌñÑ¼oÎÙ(Ò¹ì¸lr4¯¤%Ó8ë’<¹y±t9oçu>5Ú:jŒbÏ»µJi”Ö„ £°­®Oç£OëÜ›%^w3ôŒWµš}Ã5£ı)ZMãŸ®¹²´•»Çu¥ã±B·p°ûñ’†àyõ¿Ø/GHZ}˜?¾¾!K~Eı&YU³È‘ü¿´U£ãmÂ.ÉIG}´—öÄĞÇZ‘×³Û1°Ò®ğ²€íõÜ“kğ¬ !­$uáŸ_½ÎPÏ5m„ÑÇ›ïî©‹ÿhãĞ/ŞV C ãAÂïƒ—w—w8+¹QI„á¥Ø]®™eC|àH+£¢ì¨cåxYÆ	P[&°^™ˆÂ2‰,FŸšÌ.ª	Ù
½%—ÌR`•N6°K_p÷,À9åârÁPS[pşYYîù«,ûBæ\ş…"¯¢+µ=…fY£ÿ¶¦0¹·¼Â
ç©,l#Í§yÒvÏ €¥	ŠÂ‚çàUÁÁuğç£pÌz hê+©ÏÊàÌFÖzk%GÜPŒ†„Cs‚AZ+Íºvúô—'pa¤Í¤ÁØV”Ïªïj}Ë:Ì¡ç) ûbR:UÛZÈeé¦H‡B‰Pk ‰J‘ã/×‡á>Kó‡HB-€©OÃ‡úNkítƒ3Jù; ı<ßXVİÒ¡5e T¯åÎ+šsxÅ¥tü¬=È´RÚƒYØé‘â	óÅØ’ìb}`€	T|ÉÏI|š`[kVÁ5 e¸˜ı„‘¤ĞrıwƒQ`œ9±L”èïC‘’\fáÒÎä*Rmö{Çko´È„Å1pêØ‰+€çUPxÖü±‡€‚ Ôì
ZI½¡"Û¾ùÒ4E{¥	Ä©CgîTîw[í¾	Ğ¥] ŞrÅAìpÑ€7ª©÷Ì‡ò¼¸÷şOQb… ­ÙY£LŒ¤ÕÓ•Is a™_ìôñÈ(Å#¼gpèÒw.L¤K2„Ec¸‡¼s2%öó ‘& !"á¨Ç”ªY 9Ÿ`	V‹Ü:z’CØM¯|£2ÒŸT{Ü²”ñòg3´;¯èeŸGKJUñ ã¢”‰3Lt;‹<ĞÄY2¦ı°Yîç.‰</\uèé®†¤¸) 8S
´,ÍO/òYÅò6'ŠØñçÎÑ£ˆNü=Ÿ9’õ	êw~ÓF½´<•=ì›¾ĞÁ²ÅR´s Ïb˜RÀÊmĞaõéÄH"Wıå’bçbè/ZR,ºK
xØ©¢ºd,
taåÕàùò4±ngä¿«ªD´Ì@p~q7IA,¬TkÃ—Óú#ã
ŞÉbÖVgƒ}ı¼ãIÍh”–‘•“WPTRVQUSDœÙæ˜‹GÒ¼`Ã't_¼ !2u¤wBã†(T$”•[şª3äşú·öå*héÜpÓ%„<®˜F“˜g®1ÔAªJóµÚe}²íwÎyÛƒ†èÀ²M²±™ÑAÎÙ59œ¸8êƒİ.»â¬ÃÁ@LÄ‚“4v®Ú‰âøë¸#(†$E3,6‡Ëã÷óg…YJ‚3#iv<¡PªÔ­No0šÌ«ÍîpºÜ¯¤h†åxA,×¿€–d€8UêRJÕhKÖéF“ÙbµÙ³8Èát¹=^Ÿ¿×d) #Q(,‘Êä
¥J­Ñêôé7ÂğAÆ):™b,ÇwQh£Ñd¶Xmv‡Óå®Àãõ‰5õGQÌ‹–ÚkÙŠUV¶¾µÉi¶´•³ÍÎqóğZ³nÃ&¿ O ’È‚BÂ}…¥´mÇ®=ûµ`z„íA‹(»#.!ÙbÚîR¥ht“Åæp“É÷·>y,Šjk©*I{,•õD®PVQUS×ĞÔê—MGWO¿§†=«ª^oÕÆ&¦fæ–VÖ6¶vöÕ;PtËôôÜ…ËØr?ûß]”:ä¿îù_ÕîÓ²ËßÏátÕØoéöx}~¨M5"¤J+rU)NÍ°Ø./ŠÄ©¬•A‘°R¥Ö4µîÇÎÆÃ±No0šÌ«ÍîpºÜ¯O æsísãıí†Î`²Ø./ŠÄ©L®PªÔ­No0šÌ«ÍîpºÜ¯Ï†Â‘h,H #(+Iê'Kg²¹|áähU&y%kú©ÍV»ÓíéÏc0'ÓÙ|±\­7Ûİşp¤NçËõvÀ’}»?_>l[$Ã¶	{¶åú…¢	´m-ˆcÕ­)Æ²*²ÓÙ)¦U¶>ùXÑ"°å€Vå®ÿ/OnÜyğä5„	e\H¥e;®ça'i–eU7m×ã4/ë¶çu?ï÷òúöşñùõí¥~`:“ÍåÅR¹R­ÕÍV»Óí!ìÚg„B*ëâd8O¦³ùb¹ú@FP'ÈT:“ÍåÅR¹R­ÕÍV»ÓíõÃÑx2ÍËÕz³İíGêt¾\o÷Çóõş|šn˜–íÔ
—W£üšÚ~ıÌ§ŞhUP§B‘¸®~%R™\¡¬¢ª¦®¡©¥­£«§o`hdlbjfnaÉŞíNi†¿Û÷|[EÅÄ¥H• ~'à»ùı?çpòÿôkum²Ÿ[Ş³…®Î¾jƒXëœÈêÈ4ñLé
ùÉ>Xaor:ö_±„	©´ÍU#"THeÿ>˜`Kù³‰ÒÎ s«©#låòB‚â¨µæµ]„x;ªÏäq_ÄûŞÁ¨ÎQ<r¯X¹ò³NP™§¨ÏK"I¯V©0-÷şM¬Ô$[œÿş ²YX•OÎªärÎ±ëdêÒä™:–¡î	OãNje¿­´¿Ä›ı¼:ø©/Z§;yƒòÖç¾T#®‚¨Ä®Øï¡™‘ON÷ŒKl²ˆÄ%G o¬½xşˆ&·K—×TjzN6Ø`06ƒL–1“Ò—0û5ñ§ı«×Îy‚—\íòŸ'0ÉF9}<\#À“k¬ºßÙ#,Ä£¯iI88·k=“N¬ñ+ïğ¹Èx¯»ãA¬¡İ{¼%kMf RÚ‹×·İ‰È²tCÓTWVUÓ®^“vjây½¨‡·Z‚èSÏ¶š\‡ıäq'º-­Xä¹Õf±sº³êmu|Ü«íUû(Ğ2P‚Cvr°º¸çÖjÛà¤duVçwc¡Üÿ]ı»Æóóéş¦8Š[Wæ‹¼Mõ…÷Ê8$"àŸÜİ:O¦ò¤ÙL<·‰^íÍF²ÁŒ+ãGÿ,ËF[¦}mÚvL§àÕQË«/|‘½ã‚Ë'h*‘ğy	Şë|—”ğ-«+ú:ë.
š±@“URÁ!‚A”W0ÈPLUD}ôsgƒ*Ã¬³Øª:	B.Ü•t(LE\O‡ÆÄMi<øıêñ•ûj6¾‘ÁúmõFšeÄƒÇ½3½ñR!|ïS5[Ú /	¡‹Xa³A€gxÆšyØú‡ZA–·‘aníÒ¬Åë¬ã[³ÖìÏFu(Œá T`ÄœA;í3ít–;Ëíí¿Ò}tıüLW­Ğg«YÚÓÓ›zé-÷b8Êõœ0Xì28´"`$›ï7µüv[HÜ]U„0¡Œ©´±.¯ Â„2.¤ÒÆº¼B€Ê¸JëòŠ "L(ãB*m¬Ë«åL/Î[ÚbWßõoÕ¿&&<j-¼¥Ì¾¡]êGIsw–½À=Út'½"¹W]ÜJñrÔMy¯Ó°pyİ5}ò´Ú;±ãRõ(­kÈÌ¢hÔ3m+I¦‡äë:¸;Ë1›Ğ(Øƒ¡ 	åÀ°p &   À¨İâW‡D(ã"YaB£ü¦òÅPéœ	Njñxæ}tâHVœê«=ªğg–³nü<\ÉAu[iyÖ‘©z ã	 G €€k<  |$Ğš  Àv%¡Ê+æÊ¸
hcİ„sÜ’å5#Æe¢&š5ÒWğ^ØÎÙÕ¡_ÇÜÈ^¶:mˆWej¯§^H ˆ0¡Œ©´±.¯ Â„2.¤ÒÆº¼"€Ê¸JëòŠ"L(ãB*m¬Ë+ˆ0¡Œ©´±.¯ Â„2.¤ÒÆº¼2€Ê¸JëòÊ"L(ãB*m¬Ë« ˆ0¡Œ©´±.¯ Â„2.¤ÒÆºÿßï7«·?ß®5iı7übã/²îë4L¸Ğ ÃT²hºÈ1öC8Ê¥güÈ!fÖ§Ó.;:Üş13ºüxìóöÌÏ—-ã…²2À¬WPßÈ(‰>5§—Ø?ŞvøwYïš	Ğçgz|	ÆgVÜºòpÑ»şùœ’8dÒ34‘q)ÛŠ ~¡)óë[à=@Ü»aš©Òì×›ª77 —jù9f·b_lQU¹aË4ëC¢Kª¼´ÍÚ°i®µ9«EñŸ§J_E½½ş Vş¢àgùø‘^½ÈŒUy Ä•Ò¯¡W÷ÔÂ‹b§6€¾6±¶ĞÊW¥şÒoxjn|<—ã×ıŠ{vŒf1GVAš	 9T]vE…_wnC¹õN°²é §éÇDT€9Òüm!=Å,ß§"‹pkeUÅ³ê?.ş ´^\i\J …ÁãeÆ_\D—^#£O <Ç^âMñV1æFeüfn/4î2·½                                                                                                                                                                                                                                                                                   ÑF‡oı—°ß’c-1_½æÜÒ¼¯õğ©o<T±ù¡@5,{[„õ#äê”#´ÈK}ĞÃBT]d>tY1Ç˜‚ÕÙ÷Zg:,ÆR¬uŞİKY_MÕòæ¤_ÌÊ…+…b}ÈG’;Ãå/°/
È>%^
¯†–Å!mñ–ëúëø_íß;)½JÎ˜¯húòî¡À9ÕJíÀñ~—Bo9ú…²œc3R¬Hi\i»êsÛWÍFİÏÎ†>³zŸ}•>C…¤6FØTÏÖ¿œæ)è_ö™.ä3ü^ˆŒm.ÚŸ4şÙ+­Óü'™k[±‰,pÀÓ7"‰]J[çU
*ªYAÁo3£XçÓ5xlXfß`<É6B’aò)ş|su}~öökV_#Ú0û¹WÂoğê€ñ¥eÒò¹Ä
6¬)°ÍÕ XfçÚø¦.âšTaÒÅ·>A*¾{µÕ-ßôaO„¡Ñ¾‘ó”Îh|÷€öBK=¡)D7JeÃ-Œ,‡¢¨³!G°¯ã«óš)¹µ˜8ËW¿‘Bx
«ñ>ì]«şs}5-xeéB×RÕ¾åf4İçPšü÷e¸ÑÅùôK<¨ŠÃ=†œ“’Ê›‡T•.TlÙ€+;š½ÒD‘ÖS¦ H:­4ú½ÔnĞE7ÕÂMÍK¶~©´p¬à-_ëµv­o·‹ªı+T£3Ü±>h¾	k|±åäık¥–£JG%›=Zÿ{ÚŒBiÉÜd¦·Ó»,ñ%ŞİëÆë°šŠ	ÀZäZ5]+Npö¢Ë“¢üñûØ (!›ş*É0âNá‡À^¥¾ØãŒÇı³›‰Ü¼ùĞâ4ÇóCîšu¨Ô¨0Ğ¾_ÆiöÔZw?¾×rÕ¸j(k¶óÚé©V³Ø•¼Á¸ª„€,q! 
¡e¶yª-!¤ŠR…ji|Î€×ºVÇµşp³¼Œğ58¸„4v¤Òn¸ÕqV8ø…ó7ŒÂ<]JµRêÀ™vÓaé¨VıV¬«L1di79"p Ò§p¼áMXˆäæ')gcÄğ$Xù²i_2bVb·hTJˆr$âÔœ ²GQÉÖOR­D+]Àeúì˜Šex§pMÍ¬E‚íE,Ë(„0Œæ©ûš¶QË–W³Ø¯E4ÒúêííuV_­“*Ój}2!ZëÚPi5_¨ŠœbFÍ^q\µ“¬Øc(±ôZ{Øê¨ê•Ô<|{¹,‘¡e…œˆ…ÑÖ4º©ÔGØŞlØC¥°Ó¶SOYìˆlMC“*ä
¼G5uä(õÉDTÆØåÊñ¬	³¶Ûö0?f›s+<ş?;,Ö„dõç±¡†™6^]fÙ}6¦¿/.3˜B9Ïë+Vä©½rl"IY–ÍjBËyb›ÅBˆ@Ğ$ø:Ù¨†p¤U¸ìœÇT¸â»5LAŸ—f„À¹¦B]É®%lPÅF¸[µkˆ·†¦eFU¾8ÿ}l”zX.WÑ’õ)ÂÍ¦%¨çJÃmÌ
(ŞÀ¡LdYS ñÕw¡°à@ÏZcİPöv9ä‡
’#«QGW+=•2(A\‹Ä#Ål$Ÿ?dõõå:»j¾•DhÔw%“ä¯P¥íván©C9¸{m¹,ĞøjE:c3*%©5¾o6%a~ÂVŸ¾Oh|©XÊök[5µI9úš2•Qù¶Ñ¦L»4£.ÚG+¢ˆHÒŞÖú«az;ÂnÆ5ĞSTe3d•7¿§
Ø.)‹OH:=)˜ ƒóîßˆ³"¼·ë!\F}YaƒßkE+½e8ÒÚyMHo‰Ë®ô– ½%—Şt—ôR£¡À4äâĞ\œğ„ù[zXM«õÕõf£±˜#¾¬¦káe_VÓ#Ö>.Z5=âÇà¼kèDüš "‹Ió¢Ìî?|/·ˆµÁƒ¦G:jª±ÙjzÔ5O”^F¡èôq¥dmû(dozKD@áPcÿÅáHÁÂ/¥#şBDXsyd)€,¨˜	”á'İ ?¦'´ì„ 1X”^Û“ı}“§JAîCZeñ¥¼`¡–6^RA)
°hÃ›ŠLÔ
ŠŞm*h§G4,’I(u¹Ë˜Ó‹Uî^Ó2»C\Öš±Ìî~Ü
®…ÂşQ8¬H<G¨ZKœƒ¤ÈÂ‘&á\‚k¼Ç*ØÊ°D5ˆHr ÔVû[÷DçÛ³*b\µ6N4eXÉ9+e™˜p;è°‚¨jR-´
ò9¿0Œ%LÕ@¸Õ¢Âã‹ÏcƒÅÂÖ¸ Z"¢ÊšNƒP aØ^m-ë…p¨8Ã5ìÕÂ„2Â€ãİcú³=úS¸¨¬É¢B ‹Q]èhqÖùôßï[èH˜[”§€´ÿ|Ğÿšv&^¾ñVTzGìcj?„üí³B1)T|ÌQÇA³„µ}¸W±	7?âŸt!©^wk¬ Ğ0è	ŸpÁtK±Eyë7š
ï±ìú¿l]¤CÖi­‹”Û#æQê›im
2	ÑR}¹azåšò®cYd˜H)ùñ¸]°’,‹´}´à±…&Õå4î×ÇrıÆÌ¶/ÁºÈds£{À;lh4ƒ—*ë3‚y…§xï^K%wÑpDs£©JˆVrj~ŞCç–şVFbÙ¡É}Ö?ÖÏ©’Há<|œ:o'ÌÛ:¯ÉWoßšªgÑiÎÊ»sf²kÎlÃåyuÆ£jİ¯Ôp ZoçÄÄ«±÷İZtRìƒ	÷’ÇæŒ
Éc3›”?äºÙËûÚ¬Úõˆ0^!zÛî¼$lç¥›î¶Ù¹ÿÒ3OæIFøá€¼MÁ–À4Ï_)¬
äŸG/³àé¬—Ø„9 ê)ÊÑ
BLœ·ûI'`?Ù²ştÒ}?¡+İÚDJyNW,A\O¯Ş×+€|ÇöÅã–ëvŞşÇWÿü|òê—·ŸßÿıíÑÛ¿Ix4í¦9³‚ÀöÜ™k¥tàãûãïØ@“ òGHĞM–>H˜¹ĞóP¯„šÆ–¤<¶ÿQkmÜÉ„¶'¬t€wPs+üÏzŞJƒ\xÜäi‡ä‰àÉÃI‰—L&í}-"*5Œ¤-ƒ<p­sioÎÙa>lÃì)™´9
TnÃ+v9§~JÙx´	‡Ÿ4
‰p¹HØTœÍ7rÁ×ìi_}lÒ$œ¦ƒHã×úÅ˜‡ûé"BÂ¦ò„ë×&óÕ"ì¦-pîMÜ>'Ü™—$’|™;\zÿ'ê@JS<™"m‡µ'Lßo£)=#Å”ie»«§Rí‘Vœp†¶ôıJùfCcqj‚¿J·4Bğ"ºŠŠfj3ÖJ:åßHO”}ttŞ÷Ë] ZŸ'§Éº&¨ñò¼#ıšìF5ù8ätü†^ÚÂa÷‘d@M%d·&eKQ6{=¥¾v¿º©¯Šõêšw-“S0ò²fèm¿ÕŞgA¶èKŸ"_ş…I -'“/7Ky›a¯poß:<'‹"ÿB”Ü²Ò0]v3à'QŞÚEy°˜Y¼7„z‚Äóæeé•²>B]uqO/R8'’ğ²aÖsÅ–ıË©™Ÿ>âqOKIhÊĞJ>8ç9$;œºoCö;,ú¾o°TÏ—Å¾-û¾­ï›dmfQCê*-şyG ó¶ƒ™Qc“ƒØb9 $A{i{¤óX¦4¤FÅgª¬¬µÍhã×}ÉãœÖ‰!àÕv|ÜDšğnú¶>²ãæ˜Z—2Èo6"j„-ÈÛ¦{IÙÚ­—¹Áòç2¤Îûí%p/„“9ÜlzÓï:7Ú;]ßñÕwûÜ¢(]F1@…Ã&zq¬/ Ó¦f)MzÄf ‘mLøé.³eNÍÂ —p„kõ¤nHúŠ]¸'ã*^ştºüíê·ÛØŒàÚË«ßN¢ÿ9Ô›¢?5WSÂuœ´áo’‚ÖdiF,Ñ2-½%tƒ‹–Ò„Ã·#m¼ËxAœ{‚—ã¼ÿ×gfR}&ñu<FãóÛc§NÆzEğWôdà59¸o’.¾&l _üŠ,ïÉò5‰èÇÜæ(^mÛ;øŸÿLğ™LîÈÒ·L×´ÓŠÂ[‚æ)ğ&`;Ò‹ñ-KHbô†(·ø’¿!‡oˆšÜ`³¡YôšPî·Ã¥¢èB/UC[ş-KÊ5=ú{$FÍ¶á¤‘¨áÏ9üD¸dıC¯©†Ê¥	\AÓñGÈ
KäÊ)³„GïŞÿù?|<şË§¿şíäï¿şß?şùÿÿŠ“”d«¼XÿQVgõùÅ—Ë«ë›¯ßnïîÛtã–qŒ£ñ£±ñÓ÷?cúwJÿ#ÎÖËåÅMx~€İ‚ÒÃP€WìÅ+1ø¹ ™	ó(üHÀ‘Uê3+ »MğñÇ|“3d©üÉ»p²ô]zi'm6Ãl7ú?\Ø~K°Ğ@ü3,È	?ì(Ìq²,_¾´£°ÄÉR+'GÛİT/_ºQXAQ5±<ıàÀŞ/_úP3Yß‰Ât™‚ÎÒ¨Œb].0y°4öVÜ‹Ç^ä¼y@´œ6"’jÅÏ¨™,c@ÌH–ZÌÑÊ-£0È@)ÊkÇ¤·/”Tçiy»Aûûè]Ú³uz_
OÇ8ì¼Jirñ¸ÙX¾ğdÃ¥~Ş8¬¿Œ!}½°,Õ—“	÷Öl9 ¸Ù7ãÙæYØ$Ã&swÇí¼sÍÂd Ö4bì4°ÙcÁ=7T1`/õmxE´w):&zøN¯R%›}9‘“b4{Ù1±:„ ¡{J5Fî¶z^á÷1ZwKcåLRÆ˜é}ú(ÇuÙÇDåáòæşxŒ}Ö¬…ğ<çèÓxÏâı¢“ƒŒª‰Èx¡šŒ*É:ˆñ¨¶¿ô—h7•.-›4HáÓZX‰laµû{ ~-?4ŒL§ê5‘­G`èM¯™ZşP©í¶Ñ›­QûsÃşQ¸ü ã?m7¤±Mu—Ò=›ş°Y0ÆŠ>8ì!Ç‰¡­NÓ‰–®tİ ‹Ãp|Ë5ÓñM9qW‚SCËfR!¨¿—¶§‡9^ZvšL´ô4£p¬ÈpÓsM{f)pVĞd~p`Ù=@& Ê-=]M´ä4¥€ìÈğMß2½¹(‰Ä I€ÓChÏ¢¥†–œfmušP@Nd8¶gº®ø¾)…6óƒ»‡‘E1ê’È×²æ;w‚à$ò"Ã²MÓœ›®í?‡D~dØsÛš9^àyO£Ñ0‰f‘áÚn`û–c9Ï!Ñ<2¬ÙÌ4Ïµüg(ˆÛq|Ï³ç³àû$ÚÛM#Ë„¾n`{¶ã<‡H–H™®ç;–ã>‡J–ÖÜt}Óñçö3Èd9Ğ9Ïõmß
¼ç°’åF†=ìÀ÷LÓ™({;¾çxü ™2ÀIR%®eÖÌœÍ™iÏëunÆÉ”J’œ86Éó}wˆJAÃ‰”À¸Éà»ÎÌšÍ¬aVr{=›s­`ÜdeØ–éA0ÌIf=D"/2/p\sî›Ş‰H!gnZ¾9wC ÀÇw\w>|ë9rŸy ßºs•B{?F¢ 2<îº¾ëÌŸC!ÀÈy–ïø¦ÿÈZæÌ7ÇñC"Ğµ–ïxå™Ös˜´ˆ=÷L{îÍíà4²é4âx¾ã)zçGI4ƒYÔÇÜ)ì¸÷£4Mk;şfHßşA"¥§ †I|ãÍç³aêacÏ9}’S 2-ÛÙN`ÏA³¬‹gu
æ
Èš;é˜î4„ñ{İñ9a²S /ge×öß›Í\s(NJĞ¥É¿i»ò¦¶‘%ş?ŸÂ‘°,ë¶%#\É†$›Ô#É’°›€ÉÓeC0˜ÅÆ6ÄÎgİ3£ñH	G½T€ÑÜÓsôİıCfØt-¯	lÕ}$M Ğ¤åÁL¸MÖÊ‰‚›Ãòl×+¬î¢	.€°)ƒÆ —x,M`"¹-hğ,û‘4!¬Á‚Å
ÃíÇÜò:MÓ¶aqh>–$À5a•şÛòI¹°]XYtï‘ôÀ¼ç<Xå;ÏS4s`;¶úc)U-çš-×vF“ôXç?9÷GuzÙ)İı­TÅtYâc9šÿLù*i˜nË3l£|ï§¯äc2ºDÇroşSØúÁAÚ…]›WÎ´WÖ£ÉhÓ;–ÓùÏ(ÛŒØ¦Õt,Ë´ËÒJGF	}ÈövËqšFÎõÃ(„|j^6ZúS(DÏ@X1­'PM4ómÛ¶×|…õÃ”€s0¶Ç ƒô¬5ü3Ÿ@ <ˆ6-»‰ƒÑ}
} ÇàUèl¸¶—ëùRˆœÕmmšî(„×°˜7×Ë¯¤¤é&tY«i:Ş„+ªaê”›óôê‡İ Õ¢bAƒ 5ò/È¨“™4T>-Y4:¬'¡­’ß^¢ŞX¸t’ğ¸Õó×jÂÔU&–™‰ÚEmò@šr+í=Ä¨WŞ3uSáñ¡p}Ç“*Ë€å™ĞW…ÜK1Êõ!ÖTárôGV^BÑ.ÈÓÙbÁïôh¦B‡Q÷ş±»Í”İuÖ‚¨Hè»®é%'»âF#ñùıüç–©ÔiTx —´fK°ñiVjë-b§–˜ÜÕ™Ø­İM²ìXˆZ°nKõ’7ö¸A°]IRÑPôòv–f†o	Ñ¶M’s±cò¼o™ŒÚB(¤-»"º0¦Ñ&}H¯Éo¹ŒÏÒBiŞ²ƒú<
ª»	Wi@Tàœâ,}:†;ıP.y'G´Z(êõ}¢³Ñg÷‰A93§i˜µl¢Q\ûr;§Y¨¬+Á&ŸŠ²Gw;;áNğˆnŸŒH\—:·!Lë¿Å'}Ä¯„ÏàÁOA0Gı ¯Ûa‡Uæø,+d|—ø¤˜åÇ²,ñ9Ò+yrtõ†àı‚yÔrò1­mB»³Dü)	ŞàcõgòW2Ó<ë~ŠÈ‡1™°æû(ØŠ&?%İ9Lh¿à¯}âPUø€(ş†!ÁO~&Ê©şÊ0m
2e!Ñ ÙóuZŠ;ŠéeÒ¬¾PßF¿J{WÊ…úâ2ü
ÿ"Î9!æïß…øû Qÿ†Gùnû…üw¢:pÏyÀíbRekğ§}úO1ào-ú`Ô/%QÕ¯D¬ ƒ?†A±ñß¾Åƒáèú*ıö˜šïáÅ8¿}óZpŸe"täïcXƒ{ÄÓFé$BİLÄiƒŠp|ƒÆq½#u¯{¨Ã¯­¹®ÈGq×”ÎFƒjˆœº<Çè¯øä‚N›İäMË#™ø*Dşƒ
]\Eóyõ2ZUO	Sfrxiˆ¿rªë;šn,Ó(Ğ o»Šp¨ ¬¯+Ã‡ş°ñ!ŒÃLßú)í‹ˆôÇRÓ@=ËÄõÕsî
ÓqšD‚³¨ íAõßĞBbü‹„òü®”
¼ÿ/Ô+^wıË]iºò
Š(FHTtLiËˆ;SCâşwÑ“=Ÿr†¾?¨ÓOíü£)ÅU?EQA¼ãOœR²À'}•L©xS/H]†û¢üuº…ˆ4A¿V”Éæ¶ÓE¹Øİ
¤¨÷Ñøœ£ÇbL£•œUò„…A„›¹„Ğ’² 5!jFœ%nÌAñå¿pORJ¸ÅBí··t/ÄH‹ àUV<L‚ëHL¬ş§L	e}ÉÆxq\ª8*lÃ<T”^Nˆ¯Ìêy‰úSY8ÊHÔ×¢]i&V„ïÍLÒ7ƒ`E³½”àšÓ¡‘Fqõ5O0|¡NøĞKJé¢&m¨Ğ$Ó@q”‚¥Àı’L+Óû#¢£˜e(¢{L_ğK¬v”a3rÔùÚÀÅÍ~‹WÍqª9 €Ô.¥×ËÁî¢"ƒNp4ÒØ*™£sï#ÅéNÉ ùœˆØµ}ÄêI[Û÷7Ë@¦ÏÀ¡çz»¿ƒ*€2ù†™b¨8F¹YL.¢$”‚XE++C6¯‡*J÷!wÛ»DĞ—˜}æ[iõ´°ÖÓ€°ˆ“T5„"æ¬{¡~OKzc™™( ~3‚®}&Áğ7ÑU! Ò©=¦¨3Û~Â"k
MF!e»OQ¿å>¦(Ï°d¨Yn8;©/*dÀ‚_Vı(ø'Ê¾–(OUÃO(wÊc–GmÚÜ„5•óElıL$ùAoOYÍ¢ U±‰Jk³d>ƒÕ5=æèÇ1«ïª×,ÎB=Oïİãtšˆµgc@Q	ÈG**ö‚½áùáòD=	¬yO@3ïËIó9·ïóêjx›^`ŠAP=ƒ:Ó¿$-¡ÑÈsüµ†(d×ÛŒô:[Ş.ƒøğºÛ†|åK,ªp	Ë"S½€,!‡KE=×yp	ÔÃx½yàÀ­ÁE§7où½Íºçğa¸ø…×-°ô 62ô@¿xÍš…°’_8?j÷~¥0ıU˜ÁÙ|-`ék“Tˆ#£äÒ)s‘Ÿ2aĞGFÀ»£ÜÊT‘²óŠ÷°}@èÇÂ¹­ÃÎ>ôÌ£…¨}¶'»6TJñB(©‹Ó_ĞË:_Éß0Âßì<´cÃ’=	ÀÅJÂN¤½lC(T¯¨vUçz¡”°šE‡ò^å‡æÚR†ß~„èRõ²t?g&Î©báqf$hÎb¸õ‡ÃX<¸¦%Ÿ tÇ©ïÊe2‡ÁKó(.>|»ÿ~O¼yÉÈ>hÆÿæ)Ššë,ŞF„#3ÑhöndH|ìsÂÁàæFƒÓJ®ì×apj³ŠH³RVV3áà¼Réò8Xjë¡&Şhœ&hQu¤~éªç[E
á¦¥ÿ¦RÙuIÁN`S¬ËR¯ÇşéçN“õÛ?è¹å–£“’Ra+S¬‡)¤'¢ğ°qçÉx|)8GÌ¨[ã!ûè‘(EœpQºÿØGÉşFg½;ßPWv'JW½ˆápt4Ú’;Õïá$¤0	¾"wüÃ£iM«wkş’cÒ-ôdy(SÒ–³»(xö 
Ö$‰³8JÖ¤r:€4*—ÌûÏÑ.?WPĞ>)-±’]!Gë÷=s‡”xµ:'É'Ù©r9ı¸%>¶ƒù¢í¸½d>Âõj3Æı­ìƒÀµ(([>Z–ùjB#ä‹n9KvÕ‹MrÔY¥Ac}¾ÑPÇà8ìlvåùºÒP¯Ë–:n¶t ¤À ‚.^P£©‰«øDS €Mä²»‰«µ8ñ°€@™J/ƒ¦Y"*'äT‘üt-é´)A`E,,=1Ô:‡m-5çPáãT•6è9Ô59[^¥@ª• ¦.ñv|T@v'Tbá”¶u²ŸàX{Pa!J¢¬%Û:}'ÆƒU{ÔÂIƒ\S¤™Ø?ÏÚ 'y#ûŒa
A!h›a'îÄ5 C-ôÑ©!„Á‹†Œ(µ†¬38Ì.0° *\ğNR¼Â™¦¹%P4÷aÒÚSÑè~Hu^r¿›t“°K.ff_ö wÁiŞPİî¥kÓhË¥˜1ŒY®S¦VÈ8Ê(‘g)´QiÏÄ
@I$ñM~Pã‘’ñH®±r›¦¬*Õ}¨guæ¡g8Ï$¶eÔ¥G V¢yØtàğEı=ùù›Øh/“Ë¡É‡7< =‡yv“¢/ÜYæÊÍÜ•ˆÛ+»ùx‘C"ôY=yd/OYO”P–IU¸½Šqˆ$¢26t€¢şQN±¹Ó´S­†P%ş,ÔİÒ×ñ²Ù«–`m_Òâ%LõÍÁ¸`æ£8ø+íïÎ.…1_ğeØ÷Æg78[Ç´‘4…,â$ùÒ1BÃcªÎpÃÖoŸ×¿vÙ_¸Şóëİ-E‘TŒİ¿ø}Èêıı:5iºÚ/ôç+ÒŸ>or¡£áp
sX:šé:¸¹®·%U:·GÜÄmĞ€	ı0ÈG~˜äãŠ~XøQ!n_Úü÷z8Æ€MÏ/ÑıŒF"…Ô‰ÛvĞİ î&º·Ñ= Iâí’’wĞİ'Şÿ¥5uI®u½å°zX™O¨SW§>¦n¶ˆOË´Lîåe^n„)£úkÄª[¨·ÈÔ`G3Ó„~šG3+†Ÿ´Ûè«k¯
1r¡/!p»Ñ'kÇ›_1D®R¢‹I.&w)Æ{qógBÎLİ1În¹ÿÌ-kèÏ¶“ÓI%„£Q =«½—¥ı™÷n×Çoß¼ıãó™´³å{üöö&­¿¸53¦q±“Ôül{4éç¢ñ*³óÁø“í ßhL§SmjiÃ«~ÅòTªLNÓé‹á,ôŠ^±[ğêp	»Ò;iİ6[Î+[‚-ŒôÛÑ³bÚšëuCsÜºfØuKÓÁa×ÁûiOZšcœ†Ö²ã:8+¦Ötê&Ä©8šŞ‚X–Wq5×8šc45ÃˆmÍp!›–©§ÙMê2\ÍhŞJ|•,ûyË±h•L»b»±£y6ÔßĞ5Ï„ŠyÍŠaczG³¬AËø_s_ÚÕF®4ü_aúCÜCclÈj§Ç‡ 	$@VÂåéİ×x!!àÿşÖ"©¥î60sï³ÌÉànµ–R©$•ªJUğøh#ßj› ÇÃg [¾>|DjO6+Ğ¯zíÑS„æÉZc£¶Ù pêOö l„øÉö“Ú³Ç•‡H«4Õ°õ
ÁP€òå‹ÛõG% ã1´ö´ÖxºÏĞàãÚÓÇ• ç¶'ğ0õô1døO/&jz’!×“Zª©lÔ	f(Ô+›µGğããÚ3DöæÃÊ³ÚÓ§½'µÍGX¼ ×îÖÃÍÍG
{Ğİ'‚ÍÚôêhlz š§µ‡¨zãYï1â ÿlo>Ä&°ÊÆ³Ú³M‚ ş6*„™Ç˜ã46ø'A°ğlàÀ>%œV2ôæ ÷& ¬lt>¼|øpoƒ2!ÕşõÀn-‰	ú¶ê­ú«Öóu˜7PÅ_Û êI³¨û§òj8LzÑóõÑ_œ7“×eóş-0g§çUt=’sÏA&}Ë#)WÏ$3/.#QC
pË/çğâÅ2*ît^Ô?­½}Õ6ƒó³$İÇÇ‡¡œöÀßlG$Š†Øh®3Ù”eG]"Q]2xµƒ×Q»5ä‘ñ¢j¶0Úöj ‡áo»GL\Xû2Æ´P<èìµÙ3÷}(c¨¢²zÌjàİ†½yµKÄ{ŞÕ[¯Ï;Ü†”lµú„ò¶aÀhÕ]*Õû§o~zQ‚øoaDĞ¯aÙhŒ¯úGßDµÇ
„0Ã,›ã„äù*á»³ ĞƒÃ÷óıÛş%×YÁ³úš„s&4 Üê*ô5Ñ‘‘õèím5R„.zIñÁóYÏhIvíW¯şäèÓó^jdáok©?Ø~,³xe9ü·Ï‰-‡°† †`¬>Ø‹z£çë ÔKÍªÿ~6·q4N/½àª¼™¥ÿP;¸X'Ñ¸?ÉÚYŸõşÊN0cØÓèæü›r­˜»,Š-±b¡G\¸ºt¼­ #wZ±´ä/oãş‚µ³dE~¼KN^¯©ÔÅEÖ~¼>Ù;4¾ºUáiQVW‚ãÀ®Ó0òº‘Ì1‰~Îôz•A… U©$è‹æØ‹½qZ‚ÓÉO1ğ%kõóõN®¢–´Sao4FkûÏ^ŒDÿFeâ~Ğ?–Ğ®µ?¨Ça4Öãáj8‹İ¡²ÃÙ`êH ¦ ½7é‚r¸’NL*Ã.Z·ÉìP×l‚Şu†İ4‚ƒ%˜¸GÍ‚f	ÊH›§n¸´Ê¨à ñ'ÇÑÚë·a÷xÑàql¼E™}·Îİ¥lAÆıóÁ»B‹¶)X¶ÓsAµ‡Aü–2WïU4¡ÉBÃUk[±"ÉSÌ–0·\­¾F×tù5Œ'ÒÁÿîDúßš2@¤;C¤Æ
œ_ì~zƒ©¤C 4ĞK²+Ğœ ¹¶%æ‘Ü)OlæÀsòÚöÛÙ¤ïÿI·óo‡ MòİI5.ıä(Wh²ˆÃÕèoPâ‘)PC1‹Ë?l½Ùç·ş¼u¤‰#.1Ä› ‚¢+¨uYxƒ¸ a˜VøÔ¬rV¨D|.µË¶_¥°š}²ê£Óy~… 5× ñNŠôúl)ÌÏ*¢ x%¯hU[‚ğ6Kû8<¶ös’7FÇè>'Š(BGÍ#BóÖ[]Ö×)v¾P¦Î0¼‹r½X¾D?»kë ¸¾h³ *f@gÓÕ±‡®&}·4g¤®Öã_F–sD†3J(IÆT™Ëfí#€Y ¨j¡’Å"õ7|Gıáe´0‡plB]J,¼şE­£È}¶…¯ “±7˜¤Øòî 	ËT½GğNEŞçí­&~u¼¶GˆRhé£ç¡jŒ‡°dT*œµ?›Ne¨2ĞEÑà«şò…_‚^
ıª¿|‘>7ºÑœjõESï¼sHyêÍëeÏÁtÜÃ_Î$ É€>a÷CÙ†H8A2•m‰`¿T¡FËôãEÍ dgA'š 8ĞLQ~_ré æof 	Äyâ}İLÆ.É‰ã“Óû-Ÿ¼|¸V;ÌS)û¢t3û<C¯ ¨Z‚v†â³]:¨~+lWáåSf¡ª’šam§™/1šòó×b‰¯¢ÄW¹AC(Ÿnnêú—¯êËWøë™™W„L%5½2È¼"d*I”ÈAæ-„ÌÓ!3ˆŞ{ìˆ_’˜¤SO>q² eO<–Ôî©GsNxò)Oã^ö|s³TBñş¦Ïå|@ˆùEweå}òZG××Âæ}ä¼…Íû…‹x± Xs¹9rËH­ìp›í|llÖü‰Â§,?S`KÛß­¨é=!‚iJÙØ}|#üãGÂ(ş¤hú„Æ²×åVûu¥­+0cå†F«D †ÙŒ Şr(.~È«-H««ÇâíBt}äaOœ9ûü¾\G·UjÙÓÁğñ8ã Ô­O9{	®Ú”¯U~ØE?éƒf¸¨×Pr)lÿ´YÒïú ÷áÈŞÑG%7€Áf°lâ~¨>¬­•üµÖh£ı49sĞu‘Ïx@T6_=AE³Ü‰@'š(Q>Pœ*&³ëa×H_Ê>QK¹€¬%™>A“ÅÈ† »öACÕªÆnêm¸| 2'¨hÕ -6¬±‘±t¸&±Pt¾ªy#Ú ú_[#»ÚÏe&w¦u±v§(C?Z°"‹y¬¬Äq Y]¬hˆ/À÷
Û‚¢Ì\€ä«>mú‹¦‹óÎ L—R›†µ!œ:•ıh¬å@ˆM»ZßbÒU7Ù[±ùTš<Ôqºf6p´çVŒ>:óüµqqÁx‚<a<ŒªúÃª·z$ršLIÍP‚™0aî„eıC°ˆqßTr¿Tñæ ˆ´"ÅFÈJŸwú¸ù‘õ'/Â	åF´ªCÒäÉ9%…©ˆÑ‰Dº¶B1ÃläbZ
¦ı-¾ºÒ§t½K„VˆÅ0µ«F„vi,™§SO„éµ·j¡¤Q.eıÈ+1Ÿ«@4¾WeAÏ¯ ÏDÊ¥;è¦¸×Ås;±cFöÔÁSÕ§EÄWH#;7µp8ŞBÂş[dëıÇÈ¶~²­²½¸?ôğKè/òĞã`€¼ÑGöÃnÃò}aP=ÑéÉ«.¬‰ì«ÍGËØbÑœÀ–ÀKiŒ!mœÛ–^,D/Zk}µÖÂ†‚÷<òŠäĞ€ƒÌîœü(€$Qh;Àk{¸Sù †æË;++]ì7ZMëh7/ëNè¡õeÜÌ¥Î‰öl² ^Yñ	s_ E@œgg®pıÈAs¿å—Û¥©tåÇ“Ãe7}àãÔ„m/¯8œ´Ë˜¬~n²SpÂş­­áÀ.W»ú…† &%P,
Ğ.ƒ|/ ù‘î?ÀN˜˜hÔŒL*ß¢ö·1O]k8°V½¹Ó‰s;<­4€ab˜‡ÌB¼—,„Œ&‘1S„Ø™8w!SåÑÀÒå\†Å·³ã¶™p¢UèNKƒWéÂ>¡U[õ@ÙİL#vvrÊvÛgıõì·ÁB²2.•e×¿¼Ó^|F»üfe}mäpã<ŞĞ©LJe˜è«ª8 #q"Ÿ:ˆK8ß…-›Ÿ™)omú²a~Ìº1fÀ¼â6:íÇ÷¥M¤(--ÆÒñ¨b4]uD¬È‘=¨ÉÕÈèn?ÖÏ%‚GÎ8ı:k¼®b§31ı(%ÂÄdö,ì"–F¯xïptG‘Óà&	¨B^L¹˜K¶ÛaˆI'ÓíNÚ[¶Üqé^Öµº0ˆßQD²Z„¬´/ğ4f<õŒ£çŞ8‡¶LÔÒ’ÈK‰ûRu‰Ó[@ 2â Ös\ZÈVÎ÷A·,’Ï<¼KÇ…InZcH¬éUD»é$õÓŞµè¹Y­ìópäø­Ñâ¾	+õ‚á9ÒÈaAÌLO«Å,¡Aq¦yj‚9‹G’Î áÑÖö*óÄ*h”-›9ÀŠß!
.ãNgøvçÛbø Ù9ŠJp3“§ØÏúÜ™™¨õnÙ­r¤qDgÄYO¶zÇ°`ÓFWä9‹0ÍnÉÛ.;i@‚mÏñ}–”Œß1³“Ú*äh?[?.MdÈ½]è­ävS°gUOÒp_J¶$y_Šùt;#\wt°wrË°}ıCÌ ØÄ®H|9×O“š;×[?÷'lÍqëÛúÒ5-vk +-ºl4:Şäx:C} W@½#mG ™L'*¸?qT™qôc"n£Æ×›v¢AµH >(Jè&€d» <¨ î€]/ŞúÉ9iQĞ‘¼w'’şÛ‘¬uÿ?ÖK¤ÏŸù•¯£Yãİ'ÛOíí›€_1¿.	µâWÅ¤ûÌNÔ“î3;a…Àò{¦ã—Ù\ı;W±ó;–—w·Ìk7ˆ.ktSiäe“œ‚×Y£–o¼Gä¼(e^…©hk/"ƒ oÇî‹¸
ìŞ= ªwĞ;L»”´_ÒQxEOèRaP¯·_Â‚øúÕiºÓ ¯N?AØ¸¼âÕR<bñ6KÂ6€Rl·¯Q“œ]ÌCáé@ÍÉo	äSô)³§òbã¬gµFèÆ_Äâ†¨²©–­Éëf¦g’jŸ¦¸Œ·bqiË¹wošîÅÈàk÷…  'µ‘ÌMàñ¾ú±ÜË˜·Åï®øİ‰•›‡}ÜŞPIx†‡…ö<ÿÔWx³0ÅRòälÇpÎ±à ,Ç8ó Ïâiï#Š‚Mê5˜"uï9Mk×Z«9ém(;u£™İØ.É´¼¬Z§U‘zhvpÿøIó¢—˜yĞò"V¡+lîÕµÑñSÖU’”…lñdÃ²–È$ou5¶Q«—^²œ\'*m ¢RæÎ¬¹Öû—wøÀzĞ’À€¸’ <xÛkk*é6F	]i¾¦åÓ!qš|÷­¶õş6ù5¦×X¾èu _Çô:–¯SzÊ×ïİ2Ã®aºµ6£çîf_fp`V?z´ùXë4à…oCUvDæ›@ğÏ]Ì÷¨6­ï³8C×P½Â55,ĞåàÚ‚eˆñ“ìÈ¾ç2°Ó4Ö†Ü‰ôË.áêj\Ê¨%‡lĞ¤ïŞUÓ¿Ïñ,PÚ³¸)sçuŒwêkßÕŸÀÿ>üÁK#>[wŞÀ·UOÿeıiÁ`ÿìæû÷šı§eÿ©RaD*WC%ş^Éïß1Ï÷uËãé*8ü»ÌªèÇ[‹·Ö^]×Íùé¿²{İy5RU¤¨ñ4k_gz%çĞÜzâ¼ƒ’kíjıFsÆ£»â©F»g§k«gm™…¯•ïÓïƒïã³U¼òS ôæºóaÁªá–íè&ö`œ(¤@j½¾î|¤±¹>¨ëßçg7u¼]ùéÕİ|?µOæÙş©"&æôˆ·eÏÔØw|?ıu¬;_¥ß¯1ß÷Ó(àÜ4A™²ºî|…O|#Zÿ&_ÁËyS¶0ÃLyËk¹77o´çíùP=›v-lFÀwŞÆÎËz€7-}•ø.v,ØŒ´#Hã“å{Q­¯U«çüPRúX¤AñUüææ£öüI{ş¬=/£ƒ^×‚DkÜÓúó>~WŒ²›VßbgI$?#ÈÄí|dõ~Çm`F¶	9kúntéõª–:oTy:‡ãóÄ®\TÄ„®ÜÂñdÁÑ3Å÷Á(%•«°şÚ–dq”«ÊT¥ÛE)sıv9q×’e<U`EåÑéæ8Á_Æ¦hŸ¶dó ßÕ_±Ò÷ÜÜüÖ_®ĞúÙ¡‡µ†}y!àô*¦(:€Æh™õ]YL±º¾o0Æ×^ótuk®n5ŸãúàÚò,Ì‹“Ğ±ÏÔ×êøwR¯7é_­^¯³Îæ@í
\/æ³°ärœR›ó³6††GŞf3ãÊçÔPp-cáÓzıÌ‚âËº”VT¯¾×·KxSN J¦ıÄ]ÆŒ¼âkTÿÅY–u>ˆZçòõ|Us.Äˆ”C®Uö|<Ù~	‹ÚWwê2¦µSàÿÚÖšµ*wîİÇÀ(j›·÷Ü}ÿµÕ÷‡«ÆwkU/k|ÃË„üVo¬–‚v8Luã…åëåå)qAñËYº«üŞp6,ª ywû‡é ¤oÿNÇ ¬„k¨e5ìn.‚¡ÇëÛ«	¤Åj[L›®Ÿ´ƒ¤¹8Û]'N³ á¼/œ˜#PåıLA{ñ“NDÃ^”]R–VÕÓ¨JŠçØeO,÷°(‘DP\ÍWj-è§ô	d|³ÈŸpHÑ?o‹úù!„Ú(Şl1l±õ†··¼aÀ%ôõŒÒŒÔ\É?’- G}Ê,uE††Ó“&%KŒ$ËGf±YøjŞ±li)³ä	§kÈ¶³¨9sç}¶Ë)ñÉ€VëåŞBÍ” İ¼²wQ± §Ìs…7é´<ıê·uºòÿÎhûu›[9ûÓ& Œ
û˜îqôRM[%<ıXV½1(bı;ò–èt¿IA0Xn„QffA&'÷3® •'9Z*ZFş|²[ cÌÏ`<4;´ug&¾‹¢™\â-~öP˜Ãj’a Œ×3&L<€^ _ğÁc .7øóŠKúhÌ†³'Ár‡+ù¤¢¶jë‡'½°é«4BæÀĞhx£éş4êKƒjşAëÆ´o ‡rõ¬vªf®êígÉV¬b¿f!_Íx®­Â¢eõ'i$Ãº’´=úªp¿ü½†4Ğ‹ñSĞŸ–ùékAÙ1˜‘çX[Pä2úÂ9‰!h5>ÌQI¿–şIÇ¢0‰Ö-›¥MYr ”ÛÇ­5P¶–}§Ã	§Ã¼úq?ô:äGÒÍO²Ê¶Çé»cF«í£IÙ¤ôi4×éã÷õê÷pÕ^GKLö Ù ”ßÜ@œA?|ª)±Çÿ¡ÁŸĞMî´¿Â¢¯I¹doö1}îL’Üñ‘–	İ°_è›ú f¹DÄôÔ²&°ât-Œ½ÄJXJb“à k£j’8T¥° •ó“NS³qª”´x4É,NçÎ¬tÙ\®â
ÊA}
ÙævúW9ÌÖI2wgâëe“ºò‚xë¥Eu²™‘À¾ğö„êˆ©.l+1¢)áINî ³T´øCÓ'¡•HN½3íQ¬¯Dt².LG«SŸœGeYÙÜ	v‹†-uÕ—ù…ş2gÚº\VV2ƒß„BØL©Œe²ª¹7ÒEˆæ<Üò—ÓèŒ,6İkš=M“ö@|»ªÄ« ]Åó%8ÛhäÛ¬zhÛÔ¯¸½7ÿˆm(İÄ2­Ü4Œšâøj0õ~‰Ş|Ğn{Á¦€<¡Ğˆ³n[6
çÿ[O´İÜ'[±¦ÜZ2LBsVBí	ÙËïT­u¾Xp•G^ÿ÷zBùÎ'i28Oç?#ÿœs¬§á(ÇLx¾áwN‘
¢ğœ.¦ZÎõ¯¹é|öš–ı‡Õ¸¸BÚùe4Æ›µZ4üÔiz7ïq›á¬Mş“0r`R²%Ä„z#z½2q¤oÎÀ&Âëú¿Ø^µİôA{Ø½™ŒoØ¬ç„X±Ğ°–•®Shk Àã7ILÀúOsÏõÈ¦»ärWıÉÄ[›Şè»¹^k•¯¯cÍ\qñ$DÃNyÿóûÚÙŸÍïëß×UÈ$¾À°#¥SÕÕšmb»«›¨[õ5`ğ`Á‰Ç²¢utÇFqõ´|…¼ˆæŠpÌk»™d§1 qSÏùÂ;ÓZôÖF"7‘Şş6—(5N{‘Ì!6©5o4ÊUÆ>ær‰@Uì‰ÑLWµeµ„Ñåt8ìşÜèÑ	¯qFx-ÓpØä3[H‚ğL«c2\m°SGdÈøq3üDØşÓ:ÈtTêD&?|¸iÙĞ:HL">}•†`Õ_ƒ¶Š!Ã*ÍË<½H”ìIıö©ïüNªW 0¬hx‚4‡òäœ½0?ü½¨8øE¨‘Ey¡i%ØífÃÆuW4
}6©ŞPÕuìëˆwÓZ½HªQÖ<xŒÍ$ØŒ¢‰(Šwb”#ÀN¼0¢©B {IW‚3!'i¦é:HIğ°fÜ>´y*}Âp;œÖºx –<ø•A±$«7%*’(‹ÆÜqÒ9_”[—¶@ ÇãB´s¿öË¯Úæ$ü‚'Ò­ÔNù†b`^•Ñ{
ğ;úòo„&7Æ~™D*â8AmˆW‚ÚgRĞc€ø¹ïá
,vELN =ø‰H|çãlı´å~ŸœeKº1U?ªÅò}ÈŒ~.ã'fó:zÄ…NaÁ\Ö®G"HÜí!Aï19T¬&† BJÂ)”‰ZM¢P²ÿ¥ãhŸ3A-Êd²`0@(ÃòÚfyv’¦†?«öjçO$Ğ\1h3„–@„7GIW–­	l³“t¡”¿åG4Éhv!àƒÔ«j Ä8ï·Ğ,º{FVNô)fAì^Âì –Æf”7™î‹%}cø)A­±tl)YF*J¹·g¾¼[ôdˆ«ŸŸ2óÕ]f…m\&Z	ôLèeÔ_[S!N}y¢@Wæ‚ Ø­¹,Ó;¿Ywp”e`¦ñ6'3A¬i7ZBxÖ´Q(/Ã?t/ˆ.€(1#^¡ˆ‘¨tFw¢Q¶í¸&*d®LBVFwŞÄsnãÉ2å6)µô\#Îš Å•sÒà”õ’9±D	b„ÚÈ0ãi#Õ)c<^°_•‰——·“ÚË£­óã­Ãİãı“İó£½­ãİóÃw;°<Ë.LÀãÍ½ü¦ƒâí5¶R›d@éPËTà+àlCA­·í-JaÍb§ÛÏŸ¹¿R>8ó›,‘2RòÑ…QA$ÖÁËÚ9 âhÿxº/úóæ‘ñéİ§İöwvÏ!¥õ
ö›î¿Äüµ!3ó,ò‘÷4!šÀAŞ‹ß<¢0–áÊ5£1”H)$@7h!ZÛ<’ßZKùŒ•Ñã×x‘©0Gæ™<çR^º‘±‹‹³(&¸&èâÚÔó×ğ–v±€Ár…v[¡f6@å.Ú‹
…%3âµ¦­pÚÔp-Ä¥í@ujäd…aêì©
èœâjQ(WÎñ¯X€QrölA‘È0‹CâÈG²Ú¬F€ñD5›eÕ(°Ao.>o–ÔãeO%Ô8/^K2ŠÙ–×%l9š'yÒëÄyÃËÎàÎ†³É“ïàÖbªØ¡Yìí­ÅŞªbïnÍ÷Nå;º5ß‘Ê÷>¿òPÎTÚp÷¥ywG9¨0Ü®lf¬Ã\š…s^´zşPğCı~‹®Ó¾¦’ãép„âØ§èrX|åóÖ{ƒ9ÄLEa&¶¨€­‹}V^·IÜ	3ßpj×Í³I¡'îÛãÛøbËÃ‘Şp5x?8Ù§*“|¡k»øqAÈ€×ÇÍşÇ½_(«Úk°Q£U*ÆjLÿ¬=m{wuò­ùäL§ ”‡y8èb‡ß!©wa,Dl¡¬Ï“[)ìDQØÇ[ó}Tù>•äë[€*ç­O¦äÓ8(”—bp?ß
Ægã³^÷WBèÃH7Bß	n«s']ûrkÛ_
vƒÜLéS%¥êD·ÕÓ‰d=/€Í³F=o
¼_JùøIHÛeÀ¬xÅ^û \ìÁïÌC#™íÌyÉn©Âmá©«ÿzkw¾ªî|[”ÏyH9¿©œ,Ì¹ùˆ²ş¡²zEYÏ(«×Á¬^GÊ®g”c9â±„5XÆ²òÖı”ªö;Š SV£JæìRÏÀ2óµNOŞñÌlõ«BEP¸ğl#_ ƒóÎ‘úæJyv8ˆYv`™x"Gç¶ñ	U'"¾%‘Ÿ>‰vä:Úî>„+v]¢¯f[Gy)¾¶,ïhşŠ¤jå§ÄUÀdË…OGÂy’r¯„qnéiìÉ4twóEÔŠâĞZ”§ê):×ÿKÛgqôÚ†Û¢‡X8ôX¥n2Õ:‚t¾ê	º6¥ä€·ÇQÜWêõ&î²W;{égÈú9Ëy bİ~88&aâ‘²ê‰É+ƒÎs™N«štp9á-8÷]ßñt)Î@ï˜æ'ÊZN•»àÈƒh}<ı
‘RÎøúà©K”Kt7Y¨zµDiælÇ Ÿï>R©ˆñYn(_N^¦*:Ëîâ±'*ã^*—å:&'@®º¸‡Æ‰{xoÈ¹¥äÊ!?TN íÕ|`Í…YÍ•‹sÄtœvrK³’´µ€©pr«ï€y 
Dïz5KÓM‹ÃÖ…¾z]6¸=‰ÄDìÖ­Åôj¸ÉÓ½µ–Œª{ÃÂ´‰B… '1L¿ÕU ŒÊ´ÑpŸÎ´ôegeå%¦>vø•Ö³AD=ÙUÈÀÍøD´]iÉ«zD¨v û[¶êVëª±9t\ûUkõHÆ$„³½c¢¬õ÷p½‹c”±Ø>®S^,”˜fwsE5(–_„ö²šM^V]yı8•g‰}'µz„>Ï£›F;GG—Å+Ä4TÙh#PÕœ+„:Õxy…Bıúñyh£inÕÂİc:íá›‹Ó÷mì&/<üŒğµ«•9ò,¹.$ä‘Û3³¦[üsÄ?! JÒò±Kxï%¬mÍ¦á8ıM¢7hİ’|û–K‹šòM˜¸jµãï¶*‹m_ºöu!Ÿ“We±FÎ,Î2	ZJ _ÈSëËº4]C`?¢.SÄ2ÿê$…Õ¶jyk†¹œ%ÎÅÚ«XŒĞÄw,	H¥­a‰µé°—>VÙ6	Ô%ÕëÆ”Ÿ¸Rhı#Âè€€èÙ¸×Lç%ÃğªÙqzA³áûÍĞ9ãÑ»ãËÉíV|Ï3—èŒÅ…Ğ1ÆË¨rTfÁœ	ˆU“ên—i»'.Ú‹ñ¸¹hjÔUÖj–}6ğU4oõ\Ôİ[4öìë®X?Z (_kXğ3È„^°t4ù.sF²·ºn·Úë~s 	4éà8²9= FÒV»ØpÔ©%møÓ©ö|vOml>Ù|òÃ6Å—,‘®[/Á'BÛsÍ Á=»‡–==ú ‚õõœ®ûÅÃ…­‹Òzty/ÀìÂ·Y  j‡î=o´P›c V±\À;‡¦-6]G¬Â0>Hd‡¶xqf.…ØÎH€p}	A‰ö°‰ cÙ·[#wíZ3˜ë.Aâ~ò«§ïlâ³ÉÂíö¸ù‰UO1åØv¶İq+ªÛè£g=ÛXYÁ&R·±µ‡+SfŠ·ê-wÚÚzò-ù¸åôqY€Ú¶n\Öòº}ç€Aİ²[óğÆİÈ$à¯@{tàì¡óUçílÉ©æeğ|*Åà—*FïëĞMıêôô28sàğqÈ×XŒ $lpÀ%bà8ÚåëÃ]¶^… ¶Ã”ØÃhçÓÓıpuõÌ}ÎçûáóË e¹êïƒÊúôú»¶·0æÁ„÷Wøş_Ÿµ^T§wÓŒ\>E¬láAZ®>]™Ï9ÎŒwF.a®¬ln ¢mûútÖnWÇYFiŠ(ŠHvyx1l0Fc£" u¸õ|&ñ·è;tg§[g0G~õĞvapq(0Ñ= ØnÜ§  ,Òb×šSè%ôhæLm„¼FB5A	Çb—yZ8×-/Wé3Ö«zyµ­L¡Ñåí››®‡:aŒwÚB9t¡\$ø1LiÔºí"I>B¹¹¬ÿæ†ÚÆØ©fvÆ››m²Lß0z}SJ
Oh4ëpÃ&JQouàNÍÜí@B‡ÉìMèú@+¢¡ÀUˆá¦Éõ RºYtê®']Ô@(û&¼¹·ú.óQ‘Óx³¨ïöÍeªß‚Z`M8:©Xx›ÀØcÊŸ@Ûğ›¿p(ğñ0UÓGĞZí‘ıgmãOÌeç·‹.tÖ¸˜–9Ø!c€Š0­º}\ˆ5åiÏ}T¯?w»Ğ»çëub.ÎuÈM%Sè,š¥Ã®oäÖÉCù;±jAÆ§OH{ñ š®M¢A¸{)²Mì ««°”Îa=¾	şÔğ;èâTïóƒ·§³ê}Å-Kg«‚İoiŸBöşF…>R†AÂØ5¶s‚õ×oo£@Ç›º“á`¥ãMbP{ºtëhîÄy&“ûìµJ33d«¬»fnÈë´—¾?˜F	] VÜH×£øı’_•¨C—||sŞ`Ğñ"ˆ³¨ÃçSÀH|‹øÆhXÃ0MˆU;ùÎ÷ĞÿJBb´Ö0¢[z#2Mt¼Å < nuäyBa59 jˆu}çrY¡íHŒl+”át«¡ØÆÃ&^İ~‰¥ÑÙáÈ³Ä5à†.:IÛ3ÈÙÃçgM•¹ÓÍ÷(ªÃpç¹#*>’¢B'}ŸtŒsN<<ÅA™a©D«Ë¸C™8›yøh¨‘Ô‹@)*§r1aÆ»´_¾;w<Ù{GXå€Î‡¢ó™¾]cæ¯0G–s»¹”wÔ‚ÓÊ˜àzîP°ïbÏ8:S/Kç ¡Gˆ$LjÖÓ£|]ƒ|]ŸGu½.HÀ£+áx™Ø:ª¹«¹g–é±¶1®[=ÖA¿©'ÄÀ—'zBÂ$š%„HèŠ
¸öœ¯;°}öè*‹N)¡¢”ĞyˆbdÆõ±ÊeCs°è ×u<Ä£yÆaëş6ñ8NBîMu}3™’kT˜t¾'Ñ«!øˆ–£è‘S¿a*£#”yEá!¦üStŒØBı$¾=RÍ/±»Eÿı?|)­Ûò‡ã)Æ{½hlJëĞ“¾rán‘]iÇDë]äÄ¸•8Àœ;ÈÌœ‘„¥Òö‚â0£ü±}-"…Q_d
]ĞÛ† o‹å€ë’İ.WE5óWI}4V±Ç`«Üø{án9
¶	ƒ	$, Z
8Â‡ ²;ñaFy!¨š…\^íØ§Šñ8º²rM§R~ÃÔÜ¡2Ú¹ÔJAoâŞvz½&B¸²µÉœ@–°É‡ÀÅR…³°Ê.X²	Kšw\#AK‡Ü…ÏPÚºQ¯Û×pVl¹¨wÕg šïUálC—w7öÃWb-úúÄn-ñgi1‰0tjSĞíÃò©hıi³#6oOšô³ÙƒŒãt\KÎ ©LvÄ3Ly]cÌPU
 ¸üFsà†”(ˆ¡
Ç9;ÇÆH°	±ˆH™`5áğ–uwV}X*+’ş&ğ·Yä >Sàb>'ñ¨Aé¥Ú²‡<Ÿ
2/CK@óë‹T8•
çÔıgÜ’÷+6¦§ô³Ûs#õ!¦["$ïè;ñ
‹:b4:à–­ã8¿Éİf¶¯5š­ïêµŠ<‡±„·šF='j•c2nĞM±„¢°’ÜÎ×w…ÚkV£·S˜2¸ˆY|å	İ	éĞºáH¸ÓŠl'¡Çm—´ÇÛt_e›Zwğº­Vê5†1øâ¤ü¤öƒ€×¯N'ÀTç3üí9¯¹Ü€Í^&Îüùà9Ûğ³Ó ôˆ¾-•ÓÈVtâü4ú6¦'h>î°šÛ“Ğn‡½­¬ÚsDÓüEu§…§…ùBWâ^Š÷ş0†„â¼[¤¹’!â‹C¨ÿ“f†×<G‰veíğ`‡Ù½ÅPÊ"×?Ïœ˜
½ƒBîKØ_aGƒCŸÜÚÙÉ_\‚ü„:À/yÕ.1µ‰X¡v«¸»l
©bâÂ›pÁ
d W·'R’šô€õ7HäÇÃßÀ§AôàYLÉ‡¾†¨ÅâZFp@ïÑA¹Íèl‹$x¦6êNÏÄ éBñÖ7zÂs
ê­ÑóDba$±0v8 ¸ÓáSìÀÛË¯m¨Ha±>¹ÛóYû>-7û77 İ—÷äY;1YK ¦îœÀ—:ø·ëş¹YBvÈ¥pO»µ-ó­åvQ+K²;¬+è±e¸4Êt,Q¸=ï>ïÈw±ç=·sÚ=#Ù[¯Õ‡¿_½*š¡#X1Ö706uAøĞD[›	ˆâx¹;Ä.°ÙœJO É«_Pˆqr…†s˜ s4µs›^‡$ç,ı„÷hS}=ù/kUfZµğÅnQSØˆ}fŒ,ÃÜõ³ıì?È~ö±Ÿ3wpÚ?9$vïmHÆn•ÌP8Ùƒ~£ÈÜ½¤ù•lC%Û™„o[’O·ÏœCw„?Tßk…®-….€ñÀYj8o'Q1Èí<^¶$^‹Xİb¬ ”P°±[fé7²‰_á¬pˆgIö‚[.aåÀ›<ƒq_\?˜ÁF‰1ğ×	İÚÇDd¤Ûà¹0	”Ò+iù5Æy¦³íÏÍ*|€ÄÓC‚‡KáK8µ=E·Ø2—­´R})n–•ØĞú‰u
Œ°Øç=¹M+qD _½Dx½ÑÜ¹,=¡¢¯Æ¾7ÊøØL7ë×¾xèŸvö÷~Æ‚­î/mÁoı¨}óKzHJz|s5¬y£QïªÚp”gXäN±?ñ¬t†õÂ¬Ö¹ŠËÌ²^E]¯Bg8P‚åM ²¶xC+NÅ¯cıÛş‚ú¿ŞU?íB6‹µJFğ¢uIn­¥ ±áÒ3chyıÖÅ¦‹«Ås•çuôûÀxb$ıUÇù;ßÕ•¹Ú–KGÆawÌ¢B»ŸÖ¤ğÅPU&X¿³a‘ıRq2´´ÍfKğš`mˆ§ø­Áõ‘·ÔÓî¼İËêÊ*'ÌÍJøÒó» ¥HîÄ‚ñê†<“¿g+/Øê·2VW}çc$®eadZÚ`>7°A‚»­NI¿ğ>E
Š…×·9‚Œt‚ÈÂoBÊ›©zĞİÉ¹@w†B‡Œ"ˆKIBÁh’,úL Å#Iæp¿/34)G‡û	9ÜÿBnqé\A™]€6ÀedGsyŒ °‹Š©éEÆw
5A†²¡ÖÈ›ÓqÇıò ±¢]dßsuc\ ³ç7Ì:9¤x$2R„Wuj;”0({İô^öºKFåFxJeå I-ƒw{@W©Q5ë=güY{Ê!…Ig¯¦	0Âò`ÍÓõç‰4éèÕn3Ë%F™Ey|‹›i“XYòüÊ~°·l_Ù~ˆ<BÚiMÉu1Ş:Ãƒv!IMò«¼¿¾å@Ê‹J ïÇo#;1öäÃÍ—™€`°.UOˆõŒQ²ÑµbÃ}-VŠ¤ÿµF+şg„¼„·ßÄTˆÜ­°¾!ºBl¤^şè¿h)‘¢,Ì’µâK2!ÈQT%™°PÇbĞRÁø›ë(³ø¾ƒVDKú„±•Ø{§†½÷}é+W³± ¿(.ÈBäúÉ-	Ëã[Ì.™2%[•JCÉä¤ä:1ĞÃc0Ÿõ¢š¼‡Š½ãØ>‚¸¹y;øÇ-™¢Ñk¶}¦½mÇ°5[À‘ˆ{³
Évz&×rƒE%s‚¶×üTZ‹Ó:ç.eŞ`•NGx3Vi½'‚_õÈƒÑnfÄ¥p” 1Š'¹”Ø¹/c!p.ø4aÖ!VÑ#5×ÂA1m—Œ&Es‹¸ørÎ2ßVnéæåIİĞYŸôS<{’rC,s•xF‹«Ùm]ctG¢-oû&ÂVWÅÃ_n£.=„ôh¢!h»&gWëtÕA,#>:î@÷'ŠÑèfËš{Iô—Vòoèüû[˜xî/æ˜Á¶<iHøKãå¡R€	ƒ„¤Ò;LûóªEXß¨ùã4L¢Ú!»£	e9on4?}Â›Ê‰{}4ÑƒÌG÷#:¿l}¬ıö\yŞ®àí8{ı	¯Å»Mğá­-Ï-qG&|“»/9|òÂST’Ayáiº5@s*æ”à¾^šw¨|,î9®Í¾~™:g×ù'î	õzé¤öÆ/añŠrNô»Z{IŞTê¶aı¹è#8ElDE`›ò¼ÍR˜ZÍåáşZ
¡ø†ò]VóÎ„4î{>×>TQ„K>l4b£DIˆ¬“„úçÚq”‡À³¯Š•î›•š÷À¢’¯ ![~sm.€­¯{ÃVnå¶äZ‘ …Ñ¯(Üñµì‹³ó•âb‡ ­lÇU»Y,XÚ›%‚ÚÁîà†~RóıK¸Œ£gJŒ¾er°,¶÷w‹õ…ôáMQ¥—ˆ³Ç÷yÈÏûRìƒ¢²-yŠ¿nDpM2! ü’¨)aßm¹ú|Q—üŒs
sOJrãtÊ4t“„5<¢
Í	œÿå,ëE‹kUª?Ëë–­f]lc—û½ü¹¿Å6‡âwP†i%CpnÇõ¥²†fZàZÒì‹ÚpikˆÖ²ç¡6Lf°EÕ‚Ú.h0’Ñ‡ÆÑ¨±EğaÙ(ƒ†rr14ŠåŠÔ çiÄâpéHµÌ5E8ÖfY³ôà·tºğ<˜…ûYD ÁşÜã¯¢qÇ°ÂŸc+ZvmXX÷A X‚¤¾@ôìÓ“İ¥RSÿSHè`PB¼vxj1Ok©ÈxGw
<…‡«5‡YaÇÂÜz7YßJ×ÿ>.[	ipÈÄÄ)Ÿ9—ÙmŠ
’¯æhÄÜäUpß˜›8úîşâù¨f/äôª÷XĞN½3áËç¶åLåí‹“~øÁR÷X˜`±&a·÷n[ü÷şşâoòÅåß÷ş®ÿKÿ "û°Ù{µ‘}x;²3w”ÌxìR,ô°•3‚IŸT­mú<©xã¨2N+gD%0Íc`n!é2dtxëpd*Y²İİ·dÒT"ÔDß[ Ğ¤Hè.`ÄÄøJÇWaBÎİ«ê^mÛSS÷j/Ğ™ûö’àwPê»è!¥é±ƒ”&àĞàé’Œşb:‹ºci¿I
­#arIQxï1341~XyèÊkä‘OC?€0 9”uÎ"V·PßImæçÙ·e:…µßaš§gsç]Ş°Ï=ùÓ¯zrYs:Fô¤¶EÌº$˜¿@ Ú®*Ô1ào ¿ÕÛGQ¼ù¾j½$sãÊtX¡…¿ÂÔ]ñ+t`wœEø}
~šTP¾Tüw‚W‚QŞR»Ìi§²È`0§üj1
ù„<©ı^XÎŸ½Eå–5FAİM"JM]‰„,YÇâ®g× «}ŠÿP¨pBK)hÌ 3,ÇÏ
¨Äãa¿"=‡­úÒ,èØÎ¨7[~	%T°—ÕcíŞÑ5ÕÖ=á.ñsôÎ´^ğ›èM;hSò×káœ©à"q8™bvät§‡İ2¯Ê77CÜv„4&p>ûÍE•¿¦Õ^®+eË«¥â§$ÃvÛ4¢³½á0µÂĞud 	{ººÍû¶˜eNËÑ7LÙi=T‡E¢¦Ş—¨’ÓLº#ì«§‰¦<;mÈtI}›n‡ª[?TßƒÔF˜½|Èk7ğ0Zµ³Í¸–’²ï½.VÓbKpÍn»´ir+ğşVÙu&m}«ŸúJŠ—>)^Ğ,PÆ4|ò0òâ¹ì1	eZÛJDcFŠ_(ï â¸¾¿S¦İÑcLqŠ!—(W¾KJUê:ßYë­®b§<£G>Fuí·«—è ëái–ã¶=
è2}€½õMª›z Q|„"E¥tÂ(U/Ë²P…™Æò=TqÏ”€ö1İúÊÓÂ—ñ.\õŸ?d¯ù«°Ïß¸u¥ÛóçÎ§Dä<{ã¶óÆ =)ØåãŠ	úAMIIGÈŒı^ÿı,_ñMSR{o«O™váìU—ÆÈ˜É“x–]=Ù—É(KjÖg!`J/‹˜Œ*¶ªUe+²¿°ˆ„Ä¤ÅMVœ$—ò{Ôó8
ƒ>$€üéí\0ƒ Ç;{îÉAÂí£ŞƒtuSë‚“G[ßhÏQëìã’ü	X}ïì°;İwt—³¿Õ²¢q?¥2ó8ŠáìİÁŒ‡·f¤ÈÈë×­¹P=},`ÂÜ#sËü jv©–Ó“IŠ–³ø>„Z‹ÎqVàC:8ï¤ƒiExW†ÙÎ7¤+“`9&“sŞğ*òzs¥
ÆûŞR¯w`…P…°Ö>QêºÏ©†	pê A¨xA÷\9=;G\cÃŠÎŠ1Œ–ñai¯Ö‡¾ü@[ç}@Ü« Âÿ Xÿ¸ı®Õlâ0ÏP Mâók|é9g¦øŠ	ûYB@	GYÂtŒ	=_%¤”#Îrx”cªjäÂ§õ‡,Š{ŞõÌk6œ×^sÃIĞ)ø¾×|èyÍGNì5ÏŸÖh?[eÜ=ô†KYaa5å¿'B‹´°å*bdè»ğ0ö‹ÛC«J6&)9Ô»İááDWä²ˆ¿òÛB©ïg:}u	ÌÏœÑ¢û="+T(MX¹ÈëÔ,×ç.¨®:AïB(Ğ¿,aÆğt²˜T¬ ê”š¨ff€¼Rb9Ô,i†^ØÄ¶©ü:O(p˜åÅáULh‰OIşSâ)á¿ø@2Á1¼6äâ|66÷æf„7`×êÓ²¾Y¤%ümç•Ä"ë
S2w^•´¸<æÚGÔTŸªÈ«€
ú`§•ândÎ€ü
±–ŸJ˜ÖŒWÅ·²°ÓŠ52ú‰yÔO:¯ö¶Øƒ
$ŒìÍ?^e?¥'«ÏÒ’/¼¬(=_iÏTøÓßa{xâri„¿™İÿÂ†2È¶löbö«ÁÀSf0°-8îïUÄ>'Çıá …á©¤“
eŒB8Ï/¹Ó¼ÉØzWë©‘qîÑ ¬İX?4ğõ.Æ—YNî2ÇşZvÕq’9^B bÏ›tP®õ’\\{!%@	J‚_À,ûJ$v™tgÎŒl/] š@¤9<Øƒ¥á­'ı
š¯ˆ•aK:Á $ªF„_¥‡èD“¯D‰SXwÒZÂlÌHœÀfE&ÿòˆo~ÂkwÕDÜU¿¥òz;¨^“ú¤‰²Ç°Ò¥¥¹İ,”ËòÂîyEV‡~Î'3ŸnB2gé’ØŠG7´†h*0´G×ØPĞ,Á`º†Õ Û¼ŠshÕ]ÿµöóçÏ5¼‡´6÷øT®vøaıšŒãôèâZ9‹,fƒcXäAZ‡hW]kÅZEÃl6ÍƒmX7*¬‚÷å*#5ìxS¯	‰w`‚&
¶Ï<18J²XBÑİä˜_ÔGeô r. óSv•ÊuXif_wj´ŸÎı+W«E"$‘Ã¿9¯óÊõÌ’PQZ”§˜ìz;\Li…rá½(-*P\R´\µ½UWkÑÆÈGmøiÂè;ğÍÇq ª|µ«ˆ2*!Ê/k"!
×>§¤5úÒïij	{ƒä(z~Ã øn ìa¥zOY‚ÖÏ*ƒ#v–@PRB–¯¢i…ï„ÂšI%*(ƒ"Qf7ä®™QFCRGqÏ(ñæ£İ¨7CknÀVÚ^­È"»î"gÅ¨xVŒÈïTş¬Ã)?­âiğNz   ',.©ZÔÛ6•xï9×Ùy¤égn„¬Gs®Ä½°`ááí2ÚƒÜ´
‚vâ’v„*´±¦›¤¬ì¸Àáµ(#»h_äQ
cÌ)úü›ğÍäX|wITĞ÷E‡F;+å²ü/½|ëÓøÏ°9©‰‡…F>Èp}®²ÎOSsûÇŒ©ïh2(X Æ(ÍU™	3¯çwÜËky ôÑ¸
ñÊd  ‰Î\LR9ƒÌ
+¹ĞšFìEªu÷u	ÇÚàrİÕš'µ%‡\Ò{)‹zÌÃPä‰0ÖµÈ¯Z;»/·>œX9Ï¢±÷_êK«›ÖÄŞÜYÂ‚N•®)İ”Ïf®&pÊÀ>©}+u¼ê¡Ààñì¶×T@aşRÙ}7=Åªğòfz]²µ))ÿÈ‘íÚkAy˜½”ägÓ¡×*ÁDH‚Xc´÷sÃõ¡ÚO‹Dı—I¬ào ÂÔ6Lé¸t‘Â†&ÂAJÕÂ¶Còé‡bÄrç»&œ8‰è²‡ÆsC¥b\…¥¬ó¯FËdu¬b¬¨Uy]§¬ô€Ã‹Q(’ØN£ĞÙsŞ~é«GîxDï3ÎÓ[ö
ªÃ³½şñ©ü8¡Pä’â´9m·ìæ{—¡Eucq–{÷I+ƒwåyºæ°£$öåÛ¶:ê	:êıM:ú7©‡úÉCPEÀÎN´DÏõìñáÃM»„ÈîSôiİş¿Cˆ-&BI{ƒ.MÒ»?å1–é>½|®g€Xt=u-$#\û«¼zrÔ4Ği¨W²‰*ü\º£Av+É Ó'½»Q£9ÔX);¼R¹/şVîÎßÊŞ•{9,Ìµ!(’`®4,¦Úg¾@¡T|¶FÚP9yëméÎ2ÊN»2jX„gÒÚ‡$"“ 	€İ&…ÜpAŸÔş$îF`ã­Rû	«n³ƒñdĞX[×rk€
M–Ól„pûcÄ
ˆ¥Î7º‹¾`·- Eh§(=„iÆ¼ <0›¿b°‚P„C‘n®®²ËœláUÍâ#b9G®-t`«f\'ÆõUsğ¾ï)>mÜÿ|É#áÏÒ—[‰çRóê£^¦¢Œ–b¢¥XÑRvNÙ…ƒ°‚B©É>#k£¸VìU©¡UãâpÍ—óôØ%zì=~ù£FÕ¡fàü-ÒüQ Í©óÅnı¸Õí—RApK?SœId­Œ µmŞEmS…ŞŒ©7ã{ô¦çßİ›qzOZ5†Ş¼_ígÔ…¶Í¸ª‰¨qçŞ´­¿¬•…8|şx÷a;O±!/§pîaÈBRŠáÆ‹¿ÀŞA¢xqÄG»]ÍZäêqïÃ,:dÜ†qÊCCYÔü™›ã°P”FFjæ[)H5îâÚGIÛœvî#q‡›™heÂ‹òä‹òÒä•àü`½$RÔx8™ÏüJ˜´­H¨ê–ÃG0àc@zeÓš}sƒJFƒô&ÿÎ<zÄ>{8ô¡œG&84€5kXğa¡U\®ça®©®:\ÆvÄò[‹ò»¿ö-suj
hìã…lÔ<svg width="49" height="50" viewBox="0 0 49 50" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<rect x="0.0811768" y="0.429688" width="48.7233" height="48.9408" rx="12" fill="url(#pattern0_14432_6014)"/>
<defs>
<pattern id="pattern0_14432_6014" patternContentUnits="objectBoundingBox" width="1" height="1">
<use xlink:href="#image0_14432_6014" transform="matrix(0.00446429 0 0 0.00444444 -0.0022321 0)"/>
</pattern>
<image id="image0_14432_6014" width="225" height="225" xlink:href="data:image/png;base64,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   X  X      ÙÚ˜#K Û                      X      8xÍY  8 ÍY  µÀ   4  À  $”#K Û›gv0\§Gª¤ƒi¹8u             îHT1(   Tõ K   ‹Xõ K ÛH   ; ; Microsoft.Windows.Update.Orchestrator  sPOÏ‰‚G³àÜèÉvº     ˆ       DownloadUpdatesActivity PartA_PrivTags 
wilActivity ˜threadId __TlgCV__ downloadUpdateList networkCostPolicy listType          4  oo48RsrwikapKr6n.1 { 4 5 9 6 a 6 2 2 - 9 0 5 b - 4 8 7 6 - b b 7 f - c 4 8 b 7 b 6 2 2 6 a d } : F e a t u r e   u p d a t e   t o   W i n d o w s   1 0 ,   v e r s i o n   2 2 H 2 , { 2 2 d f 4 7 3 b - 8 0 c 7 - 4 9 6 2 - 9 4 2 9 - e 9 b e 7 6 3 5 8 0 c e } : A S U S   -   S y s t e m   -   8 / 7 / 2 0 1 9   1 2 : 0 0 : 0 0   A M   -   1 . 0 . 0 . 1 2 , { 0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b } : R e a l t e k   S e m i c o n d u c t o r   C o r p .   -   A u d i o   D e v i c e ,   O t h e r   h a r d w a r e   -   R e a l t e k   H i g h   D e f i n i t i o n   A u d i o , { b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b } : G o o g l e ,   I n c .   -   O t h e r   h a r d w a r e   -   A n d r o i d   B o o t l o a d e r   I n t e r f a c e , { 2 0 5 9 5 7 5 f - 8 e 0 5 - 4 2 b 3 - 9 b 3 0 - 9 c a c 4 b 5 f 4 9 4 d } : S e c u r i t y   I n t e l l i g e n c e   U p d a t e   f o r   M i c r o s o f t   D e f e n d e r   A n t i v i r u s   -   K B 2 2 6 7 6 0 2   ( V e r s i o n   1 . 4 1 9 . 5 1 0 . 0 )   -   C u r r e n t   C h a n n e l   ( B r o a d ) , { d 6 c 5 9 2 b 9 - 4 3 b 7 - 4 a 9 0 - 9 5 b 0 - 1 a 8 1 c 3 d f 2 a 2 1 } : W i n d o w s   M a l i c i o u s   S o f t w a r e   R e m o v a l   T o o l   x 6 4   -   v 5 . 1 2 9   ( K B 8 9 0 8 3 0 ) , { 3 2 7 3 e 6 f 1 - 6 5 3 2 - 4 4 7 3 - b a 2 2 - 4 d 6 5 6 c b b 7 4 a e } : 2 0 2 0 - 1 1   C u m u l a t i v e   U p d a t e   f o r   . N E T   F r a m e w o r k   3 . 5   a n d   4 . 8   f o r   W i n d o w s   1 0   V e r s i o n   1 9 0 3   f o r   x 6 4   ( K B 4 5 8 0 9 8 0 )   U n r e s t r i c t e d   N o r m a l    h †À   4  À  ’x–#K Û›gv0\§Gª¤ƒi¹8u              >›¸1(   Tõ K   ‹Xõ K ÛH   ; ; Microsoft.Windows.Update.Orchestrator  sPOÏ‰‚G³àÜèÉvº        		 ActivityError PartA_PrivTags 
wilActivity ˜hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage                €onecore\enduser\windowsupdate\muse\orchestrator\common\lib\taskmanager.cpp P  usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity    1¯À   4  À  ’x–#K Û›gv0\§Gª¤ƒi¹8u           @  â¸1(   Tõ K   ˆXõ K ÛH   ; ; Microsoft.Windows.Update.Orchestrator  sPOÏ‰‚G³àÜèÉvº     8   ,, ActivityError PartA_PrivTags 
wilActivity ˜hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage failureId failureCount function             €onecore\enduser\windowsupdate\muse\orchestrator\common\lib\taskmanager.cpp P  usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity          2À   4  À  ÙÚ˜#K Û›gv0\§Gª¤ƒi¹8u           @  ı4(                   H   ; ; Microsoft.Windows.Update.Orchestrator  sPOÏ‰‚G³àÜèÉvº     ¸    © ©  TaskModified PartA_PrivTags 
wuDeviceid TaskName NextRuntime LastRuntime LastResult ‡ModificationType TaskState MissedRuns WakeToRun „ActionPath ActionArg               »0ãÂEÜĞ@o5áuåÊA C   P o w e r   D o w n l o a d   k          è
     (         % s y s t e m r o o t % \ s y s t e m 3 2 \ u s o c l i e n t . e x e   S t a r t D o w n l o a d   V e rÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿ   8         N!*K Û                      8      8ÍY  8xÍY   À   4  À  ÙÚ˜#K Û›gv0\§Gª¤ƒi¹8u              Ho“4(                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     @    7 7  Running download NetworkCostPolicy UpdateListType  U n r e s t r i c t e d   N o r m a l   ¤À   4  À  ÙÚ˜#K Û›gv0\§Gª¤ƒi¹8u              +™4(   Tõ K   ‹Xõ K ÛH   ; ; Microsoft.Windows.Update.Orchestrator  sPOÏ‰‚G³àÜèÉvº        		 ActivityError PartA_PrivTags 
wilActivity ˜hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage               ¡$€onecore\enduser\windowsupdate\muse\orchestrator\core\updatehandlers\wuupdatehandlers\downloadhandler.cpp    usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity    t hÍÀ   4  À  ÙÚ˜#K Û›gv0\§Gª¤ƒi¹8u           @  ök™4(   Tõ K   ˆXõ K ÛH   ; ; Microsoft.Windows.Update.Orchestrator  sPOÏ‰‚G³àÜèÉvº     8   ,, ActivityError PartA_PrivTags 
wilActivity ˜hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage failureId failureCount function            ¡$€onecore\enduser\windowsupdate\muse\orchestrator\core\updatehandlers\wuupdatehandlers\downloadhandler.cpp    usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity          o uÀ   4  À  ¼#K Û›gv0\§Gª¤ƒi¹8u              f6ÿQ(                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     (       Info Message message        G e t R e s e r v e M a n a g e r L o a d e r   r e s u l t :   0 x 0 0 0 0 0 0 0 0     À   4  À  ¼#K Û›gv0\§Gª¤ƒi¹8u           @  DR(                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     P    G G 
UpdatePromotedBySerialization PartA_PrivTags 
wuDeviceid updateId         »0ãÂEÜĞ@o5áuåÊ"¦–E[vH»Ä‹{b&­À   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u              ’.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     (       Info Message message        U s i n g   r e s e r v e   s p a c e   f o r   d o w n l o a d   ÿÿÿÿÿÿ©À   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u           €  r™š.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        eg4odfjeWUSvEgcs.1.3.2        "¦–E[vH»Ä‹{b&­           »0ãÂEÜĞ@o5áuåÊ   R S : 2 0 C F C   ÿÿÿÿÿÿÿªÀ   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u           €  n¬Ó.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.6.11        ;Gß"Ç€bI”)é¾v5€Î           »0ãÂEÜĞ@o5áuåÊ   R D : 2 F E A 7   ÿÿÿÿÿÿâÀ   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u           €  ”`ğ.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.5.11        FHÊ%d@‰z
t‡?»           »0ãÂEÜĞ@o5áuåÊ   0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿ   ¸  ¸      °Zª*K Û                      ¸      8xÍY  8 ÍY  âÀ   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u           €   .                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.4.12        YB³ê¨ÖBİ8F¦ «           »0ãÂEÜĞ@o5áuåÊ   b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b    { 2 2éÀ   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u           €  ÉJ.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        eg4odfjeWUSvEgcs.1.1.3        ¹’ÅÖ·CJ•°Ãß*!È           »0ãÂEÜĞ@o5áuåÊ   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0   6 a 6 aêÀ   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u           €  b)i.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.1.17        ñæs22esDº"Mel»t®É           »0ãÂEÜĞ@o5áuåÊ   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0    0 - 9À   4  À  N!*K Û›gv0\§Gª¤ƒi¹8u              òí„.                   H   ; ; Microsoft.Windows.Update.Orchestrator  sPOÏ‰‚G³àÜèÉvº     (       Info Message message        A c t i v e   h o u r s   p o l i c i e s   c h e c k   c o m p l e t e d .    t4À   4  À  ƒ’*K Û›gv0\§Gª¤ƒi¹8u              z\8.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     (       Info Message message        U s o S e s s i o n   u p d a t i n g   d o w n l o a d   w a i t   e n t e r e d   m i n u t e s   t o   2 9 8 5    ; ÂÀ   4  À  °Zª*K Û›gv0\§Gª¤ƒi¹8u           @  Îú0.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     À    ³ ³  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive „wuDeviceid updateScenarioType flightID errorCode ‡UpdateStatus updateState networkCostPolicy             eg4odfjeWUSvEgcs.1.3.3 "¦–E[vH»Ä‹{b&­    »0ãÂEÜĞ@o5áuåÊ   R S : 2 0 C F C                 xe   ÃÀ   4  À  °Zª*K Û›gv0\§Gª¤ƒi¹8u           @  Êr1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     À    ³ ³  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive „wuDeviceid updateScenarioType flightID errorCode ‡UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.6.12 ;Gß"Ç€bI”)é¾v5€Î    »0ãÂEÜĞ@o5áuåÊ   R D : 2 F E A 7                 gContè À   4  À  °Zª*K Û›gv0\§Gª¤ƒi¹8u              Îø1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     P    F F  Regulated update(s) to download were found in non-interactive mode   âÀ   4  À  °Zª*K Û›gv0\§Gª¤ƒi¹8u           €  ”‡1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.5.12       FHÊ%d@‰z
t‡?»           »0ãÂEÜĞ@o5áuåÊ   0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b   D o w ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿ   Ğ  Ğ      ì
»*K Û                      Ğ      8ÍY  8xÍY  ûÀ   4  À  °Zª*K Û›gv0\§Gª¤ƒi¹8u           @  Në1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     À    ³ ³  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive „wuDeviceid updateScenarioType flightID errorCode ‡UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.5.13 FHÊ%d@‰z
t‡?»    »0ãÂEÜĞ@o5áuåÊ   0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b   ¢$€          reTypûÀ   4  À  ½¬*K Û›gv0\§Gª¤ƒi¹8u           @  j*1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     À    ³ ³  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive „wuDeviceid updateScenarioType flightID errorCode ‡UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.4.13 YB³ê¨ÖBİ8F¦ «    »0ãÂEÜĞ@o5áuåÊ   b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b                   ; À   4  À  ½¬*K Û›gv0\§Gª¤ƒi¹8u           @  ¤31.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     À    ³ ³  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive „wuDeviceid updateScenarioType flightID errorCode ‡UpdateStatus updateState networkCostPolicy             eg4odfjeWUSvEgcs.1.2.7 _WY ³B›0œ¬K_IMÈ    »0ãÂEÜĞ@o5áuåÊ   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0                      À   4  À  ½¬*K Û›gv0\§Gª¤ƒi¹8u           @  º<1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     À    ³ ³  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive „wuDeviceid updateScenarioType flightID errorCode ‡UpdateStatus updateState networkCostPolicy             eg4odfjeWUSvEgcs.1.1.4 ¹’ÅÖ·CJ•°Ãß*!È    »0ãÂEÜĞ@o5áuåÊ   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0                 rator À   4  À  ½¬*K Û›gv0\§Gª¤ƒi¹8u           @  sD1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     À    ³ ³  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive „wuDeviceid updateScenarioType flightID errorCode ‡UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.1.18 ñæs22esDº"Mel»t®É    »0ãÂEÜĞ@o5áuåÊ   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0                 .UpdabÀ   4  À  ½¬*K Û›gv0\§Gª¤ƒi¹8u              ğØG1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     (       Info Message message        D o w n l o a d   P r o g r e s s :   p r o g r e s s S t a t e = 1 ,   P e r c e n t   c o m p l e t e   =   0 ,   t o t a l B y t e s T o D o w n l o a d = 7   ƒi¹8u4À   4  À  ½¬*K Û›gv0\§Gª¤ƒi¹8u              ü4u1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     (       Info Message message        U s o S e s s i o n   u p d a t i n g   d o w n l o a d   w a i t   e n t e r e d   m i n u t e s   t o   2 9 8 5   .11 è À   4  À  ì
»*K Û›gv0\§Gª¤ƒi¹8u              Ø˜Ê=.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     P    F F  Regulated update(s) to download were found in non-interactive mode   âÀ   4  À  ì
»*K Û›gv0\§Gª¤ƒi¹8u           €  2gÚ=.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :ŞÇ.DJ‘¢R"ì.Íñ     ¨        	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode ‡wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.4.14       YB³ê¨ÖBİ8F¦ «           »0ãÂEÜĞ@o5áuåÊ   b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿ      casing: null,
                        def: opts.staticDefinitionSymbol || lmnt,
                        placeholder: opts.staticDefinitionSymbol !== undefined ? lmnt : undefined,
                        nativeDef: lmnt
                    });
                })), escaped = !1; else {
                    var maskdef = (opts.definitions ? opts.definitions[element] : undefined) || Inputmask.prototype.definitions[element];
                    if (maskdef && !escaped) {
                        for (var prevalidators = maskdef.prevalidator, prevalidatorsL = prevalidators ? prevalidators.length : 0, i = 1; i < maskdef.cardinality; i++) {
                            var prevalidator = prevalidatorsL >= i ? prevalidators[i - 1] : [], validator = prevalidator.validator, cardinality = prevalidator.cardinality;
                            mtoken.matches.splice(position++, 0, {
                                fn: validator ? "string" == typeof validator ? new RegExp(validator, opts.casing ? "i" : "") : new function() {
                                    this.test = validator;
                                }() : new RegExp("."),
                                cardinality: cardinality || 1,
                                optionality: mtoken.isOptional,
                                newBlockMarker: prevMatch === undefined || prevMatch.def !== (maskdef.definitionSymbol || element),
                                casing: maskdef.casing,
                                def: maskdef.definitionSymbol || element,
                                placeholder: maskdef.placeholder,
                                nativeDef: element
                            }), prevMatch = mtoken.matches[position - 1];
                        }
                        mtoken.matches.splice(position++, 0, {
                            fn: maskdef.validator ? "string" == typeof maskdef.validator ? new RegExp(maskdef.validator, opts.casing ? "i" : "") : new function() {
                                this.test = maskdef.validator;
                            }() : new RegExp("."),
                            cardinality: maskdef.cardinality,
                            optionality: mtoken.isOptional,
                            newBlockMarker: prevMatch === undefined || prevMatch.def !== (maskdef.definitionSymbol || element),
                            casing: maskdef.casing,
                            def: maskdef.definitionSymbol || element,
                            placeholder: maskdef.placeholder,
                            nativeDef: element
                        });
                    } else mtoken.matches.splice(position++, 0, {
                        fn: null,
                        cardinality: 0,
                        optionality: mtoken.isOptional,
                        newBlockMarker: prevMatch === undefined || prevMatch.def !== element && null !== prevMatch.fn,
                        casing: null,
                        def: opts.staticDefinitionSymbol || element,
                        placeholder: opts.staticDefinitionSymbol !== undefined ? element : undefined,
                        nativeDef: element
                    }), escaped = !1;
                }
            }
            function verifyGroupMarker(maskToken) {
                maskToken && maskToken.matches && $.each(maskToken.matches, function(ndx, token) {
                    var nextToken = maskToken.matches[ndx + 1];
                    (nextToken === undefined || nextToken.matches === undefined || !1 === nextToken.isQuantifier) && token && token.isGroup && (token.isGroup = !1, 
                    regexMask || (insertTestDefinition(token, opts.groupmarker.start, 0), !0 !== token.openGroup && insertTestDefinition(token, opts.groupmarker.end))), 
                    verifyGroupMarker(token);
                });
            }
            function defaultCase() {
                if (openenings.length > 0) {
                    if (currentOpeningToken = openenings[openenings.length - 1], insertTestDefinition(currentOpeningToken, m), 
                    currentOpeningToken.isAlternator) {
                        alternator = openenings.pop();
                        for (var mndx = 0; mndx < alternator.matches.length; mndx++) alternator.matches[mndx].isGroup = !1;
                        openenings.length > 0 ? (currentOpeningToken = openenings[openenings.length - 1]).matches.push(alternator) : currentToken.matches.push(alternator);
                    }
                } else insertTestDefinition(currentToken, m);
            }
            function reverseTokens(maskToken) {
                maskToken.matches = maskToken.matches.reverse();
                for (var match in maskToken.matches) if (maskToken.matches.hasOwnProperty(match)) {
                    var intMatch = parseInt(match);
                    if (maskToken.matches[match].isQuantifier && maskToken.matches[intMatch + 1] && maskToken.matches[intMatch + 1].isGroup) {
                        var qt = maskToken.matches[match];
                        maskToken.matches.splice(match, 1), maskToken.matches.splice(intMatch + 1, 0, qt);
                    }
                    maskToken.matches[match].matches !== undefined ? maskToken.matches[match] = reverseTokens(maskToken.matches[match]) : maskToken.matches[match] = function(st) {
                        return st === opts.optionalmarker.start ? st = opts.optionalmarker.end : st === opts.optionalmarker.end ? st = opts.optionalmarker.start : st === opts.groupmarker.start ? st = opts.groupmarker.end : st === opts.groupmarker.end && (st = opts.groupmarker.start), 
                        st;
                    }(maskToken.matches[match]);
                }
                return maskToken;
            }
            var match, m, openingToken, currentOpeningToken, alternator, lastMatch, groupToken, tokenizer = /(?:[?*+]|\{[0-9\+\*]+(?:,[0-9\+\*]*)?\})|[^.?*+^${[]()|\\]+|./g, regexTokenizer = /\[\^?]?(?:[^\\\]]+|\\[\S\s]?)*]?|\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9][0-9]*|x[0-9A-Fa-f]{2}|u[0-9A-Fa-f]{4}|c[A-Za-z]|[\S\s]?)|\((?:\?[:=!]?)?|(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[()|\\]+|./g, escaped = !1, currentToken = new MaskToken(), openenings = [], maskTokens = [];
            for (regexMask && (opts.optionalmarker.start = undefined, opts.optionalmarker.end = undefined); match = regexMask ? regexTokenizer.exec(mask) : tokenizer.exec(mask); ) {
                if (m = match[0], regexMask) switch (m.charAt(0)) {
                  case "?":
                    m = "{0,1}";
                    break;

                  case "+":
                  case "*":
                    m = "{" + m + "}";
                }
                if (escaped) defaultCase(); else switch (m.charAt(0)) {
                  case opts.escapeChar:
                    escaped = !0, regexMask && defaultCase();
                    break;

                  case opts.optionalmarker.end:
                  case opts.groupmarker.end:
                    if (openingToken = openenings.pop(), openingToken.openGroup = !1, openingToken !== undefined) if (openenings.length > 0) {
                        if ((currentOpeningToken = openenings[openenings.length - 1]).matches.push(openingToken), 
                        currentOpeningToken.isAlternator) {
                            alternator = openenings.pop();
                            for (var mndx = 0; mndx < alternator.matches.length; mndx++) alternator.matches[mndx].isGroup = !1, 
                            alternator.matches[mndx].alternatorGroup = !1;
                            openenings.length > 0 ? (currentOpeningToken = openenings[openenings.length - 1]).matches.push(alternator) : currentToken.matches.push(alternator);
                        }
                    } else currentToken.matches.push(openingToken); else defaultCase();
                    break;

                  case opts.optionalmarker.start:
                    openenings.push(new MaskToken(!1, !0));
                    break;

                  case opts.groupmarker.start:
                    openenings.push(new MaskToken(!0));
                    break;

                  case opts.quantifiermarker.start:
                    var quantifier = new MaskToken(!1, !1, !0), mq = (m = m.replace(/[{}]/g, "")).split(","), mq0 = isNaN(mq[0]) ? mq[0] : parseInt(mq[0]), mq1 = 1 === mq.length ? mq0 : isNaN(mq[1]) ? mq[1] : parseInt(mq[1]);
                    if ("*" !== mq1 && "+" !== mq1 || (mq0 = "*" === mq1 ? 0 : 1), quantifier.quantifier = {
                        min: mq0,
                        max: mq1
                    }, openenings.length > 0) {
                        var matches = openenings[openenings.length - 1].matches;
                        (match = matches.pop()).isGroup || ((groupToken = new MaskToken(!0)).matches.push(match), 
                        match = groupToken), matches.push(match), matches.push(quantifier);
                    } else (match = currentToken.matches.pop()).isGroup || (regexMask && null === match.fn && "." === match.def && (match.fn = new RegExp(match.def, opts.casing ? "i" : "")), 
                    (groupToken = new MaskToken(!0)).matches.push(match), match = groupToken), currentToken.matches.push(match), 
                    currentToken.matches.push(quantifier);
                    break;

                  case opts.alternatormarker:
                    if (openenings.length > 0) {
                        var subToken = (currentOpeningToken = openenings[openenings.length - 1]).matches[currentOpeningToken.matches.length - 1];
                        lastMatch = currentOpeningToken.openGroup && (subToken.matches === undefined || !1 === subToken.isGroup && !1 === subToken.isAlternator) ? openenings.pop() : currentOpeningToken.matches.pop();
                    } else lastMatch = currentToken.matches.pop();
                    if (lastMatch.isAlternator) openenings.push(lastMatch); else if (lastMatch.alternatorGroup ? (alternator = openenings.pop(), 
                    lastMatch.alternatorGroup = !1) : alternator = new MaskToken(!1, !1, !1, !0), alternator.matches.push(lastMatch), 
                    openenings.push(alternator), lastMatch.openGroup) {
                        lastMatch.openGroup = !1;
                        var alternatorGroup = new MaskToken(!0);
                        alternatorGroup.alternatorGroup = !0, openenings.push(alternatorGroup);
                    }
                    break;

                  default:
                    defaultCase();
                }
            }
            for (;openenings.length > 0; ) openingToken = openenings.pop(), currentToken.matches.push(openingToken);
            return currentToken.matches.length > 0 && (verifyGroupMarker(currentToken), maskTokens.push(currentToken)), 
            (opts.numericInput || opts.isRTL) && reverseTokens(maskTokens[0]), maskTokens;
        }
    }, Inputmask.extendDefaults = function(options) {
        $.extend(!0, Inputmask.prototype.defaults, options);
    }, Inputmask.extendDefinitions = function(definition) {
        $.extend(!0, Inputmask.prototype.definitions, definition);
    }, Inputmask.extendAliases = function(alias) {
        $.extend(!0, Inputmask.prototype.aliases, alias);
    }, Inputmask.format = function(value, options, metadata) {
        return Inputmask(options).format(value, metadata);
    }, Inputmask.unmask = function(value, options) {
        return Inputmask(options).unmaskedvalue(value);
    }, Inputmask.isValid = function(value, options) {
        return Inputmask(options).isValid(value);
    }, Inputmask.remove = function(elems) {
        $.each(elems, function(ndx, el) {
            el.inputmask && el.inputmask.remove();
        });
    }, Inputmask.escapeRegex = function(str) {
        var specials = [ "/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\", "$", "^" ];
        return str.replace(new RegExp("(\\" + specials.join("|\\") + ")", "gim"), "\\$1");
    }, Inputmask.keyCode = {
        ALT: 18,
        BACKSPACE: 8,
        BACKSPACE_SAFARI: 127,
        CAPS_LOCK: 20,
        COMMA: 188,
        COMMAND: 91,
        COMMAND_LEFT: 91,
        COMMAND_RIGHT: 93,
        CONTROL: 17,
        DELETE: 46,
        DOWN: 40,
        END: 35,
        ENTER: 13,
        ESCAPE: 27,
        HOME: 36,
        INSERT: 45,
        LEFT: 37,
        MENU: 93,
        NUMPAD_ADD: 107,
        NUMPAD_DECIMAL: 110,
        NUMPAD_DIVIDE: 111,
        NUMPAD_ENTER: 108,
        NUMPAD_MULTIPLY: 106,
        NUMPAD_SUBTRACT: 109,
        PAGE_DOWN: 34,
        PAGE_UP: 33,
        PERIOD: 190,
        RIGHT: 39,
        SHIFT: 16,
        SPACE: 32,
        TAB: 9,
        UP: 38,
        WINDOWS: 91,
        X: 88
    }, Inputmask;
});