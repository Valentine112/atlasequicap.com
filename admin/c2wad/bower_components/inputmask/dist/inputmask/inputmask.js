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
            for (getTests(maskL + 1).length > 1 &&>x	󡀱�ܟ�t��7���X����@07zz5�~}5.��Lg���$��3��O���+�z���
:��uf�(�6Ds�=F��pa�R�N�"h�RF�bMWt�uAKG�@�k�f��S#���d���KL�1a1�rfjuy�����F��\(�+)Dvog�;.�A'S��b���q�}�@��C�U�H��3*}��N���:2�m*��tu�J��I�@-�ÅO^�� =`���;~���C�iOE=Y�Is�I��Q1a�wz����^��C��\�� M�(;��f��S{>����[���.z�c����Wb� >���=�����b��s�m�?"���Ѣ׵ �)"��B�ٮ��cl���1���5>���/W5!XP�$Q�j<�u'�DN ɕ��]�t�XcM���#���^}$a|��1�T�(�0~r3�3i!��w�!�~�tZR"��D^�%!��8����ϯ腰�m�Bb�c�Tq��%�ݩ'��>D�̂���#`�3�w�J(-�c���%�=���v{WY���YV��A���z��8�I4�����ChAl��!v4\�-���5[�߱Nó�(4|�q܂�+&k��&*1����$�PG�Xw	�1 �#��/��*�1$S�]0@�	�(�|I�;�^�M�=������|Uu���GxQp�g�l�ǁ( '���ql��>�2ғ��&R!?�d�n��S�M(�� ��,��X�T̙
Tpk���	 �h�f֑�W�P�v�+�D���*�{��҉�Y�-Y����	�s�u���a�e,��w!�`�hx�k���w���Nχߦ�*[`P߿�\�V������Ч2���%0x����ԁ��c:���z�m�po�մVDF�3�b�M�r�S^1.���T�YG;�)�Tc.���ry4�#�)BE슶�ub8�٣���Ýs˼�=�pd�c��~ ��014�2�l<Sb�aN6|�>�6K�þO�ZI�(�}�+�_XVw�� 8ͮ!�uuq
ky�{�Sqe\ �%�c��H4 HwD<=�rz�j�VÂ"��G���av¸�`��$nAĘ�4&BQ�������V�����eg<ID��FJ���:�#zP���߈q���T��4~=i�Q� �\�@�h$D3D�0*�:���e6b'����4T*t��HeM"�j\�Ѝ24����1��;EOb,9Ժ�ϭ�,������x�`��]�ν�q��$�L��Z��N\��G{��f��L�%Q�����O�'�H��=��0i����B+N�
T"��Sz���ŉj��C�v'>5�N�~D�M�{�1z�}�BW]:A�|�� �r�N�_��+�1��J�U�ڻR�x��Q�7�R�|k��<K���	����0��ժ�&z�N���T;E~�|0j ��S��	('�X1O��gP2��#��Y[ؘ�Y��{;� Y���D�*[�0֨}P'G+�~�>���k&K�H�q�������2�S�x*ԓ3H���*̳��D�Rm�j�bhHDe�0�5���i�E/}e���	C��1��[�KAx�����;�  %��/���1�΁��� p��ȅ�Ӎ"LOd?��d��^��vo~n�΀�n\T�0�7�,��/>���4��g$lڤ���m�>�{5g�2�B|�r��{O<�.<�	�?�@,�Ǻ��q��g��wv�i�(00 �����㍩C~p��R\<��̜����������o�w$��q���.�8$�كF8#Z�Q�Z��\�}�罪�[�|{��<��Pt$��ŵ�T6�fnQ2H��	�P�r]�f����/$p8ؔ�<'
��0�-�.��|ܒX�FR�sx'q2� p;���ە���lGyy�Ĵ��(G�i*p�A]��"D�O����i��`�|�j�l�%�B�X���D˒�Ӡ�����
���[;���wrc�]�F�d_�$ ����}��Ǚ|0�<�#��z�<)��E�h�!$a�c@s�62m²�.�A�X���gr0��Y�5�xƌ�E�T�����Y�G��cF�e��`�5@F0��l�XM8����ψ%�5{ۙ_�����L���� a��[��~*�}�h���el��w�/0Ņ&��~ܷ�
�*Y�/m/|�J\4<��;B�$�ɚƵ��w]�]E��mΨgI�S�EV��oM�������������C5�Fr%�ʧ�}��=#��S���衿j���:����l{[&'_3���hA�@�b�d�Ĵ��;�����������%��e׉���{_y�Jkv�N�rx��_��V^�mI���
���*����[pu7�s�QeQ�V.t�Vl>�/%rU@�n�`�z��َ|8~�յG���G�-W�ɺ�e d�#�"��ek�T�~�����>�`�  G�F3d�7�It��Ƥ�~f�h�d�s���E�[� hg^��t泒�'J9��i���Ѐ]-�^��u��Vf�H ��~Q`�3VI�P�����@J�r���$w��76�Ə� �{�]�|B� F(N'��Wč�`�{N��,�@`�+���c%8wix%F����%���M R� �T
n~!�X'�[��v4�/�r�?�ؿ����X�u0g#�ޞQb��d�R!��0�%��$t��C'FH��4��C�ק��z|X�5<��4ݶ3��#�bE\�1G�v�"
P����o��f���iD9������EYX�Ae#;T� %���{�D�Tv/��6���#�z�d̐�0�N^pNK��vgGcC1E$��`��c,�/�wu�_
� ��ץh2���R� �� �W?� ���~����
�?��~����&\	)H$'oW����^8ͫ�{N��^����^2��1�[4�~vo�N@l{�.��&����4i51�.�,:"��!�ioG��N��(
�����Б��kaYS��Ĉ��)Y��1'�w튵�μ%0���<�<�i�~S�={",C�3LI��%Q��
ͫ͠��&EI]c�@��	���h�ZN�LB�:�p4X���)�����6y d��������8��{0ڵt��DH��)��ǟ��������1�s�
���	ʋ�F"����v��`�^;��z����va�D��8r�ūj��EO����9s�p")� \�#�N}z�z1�d�$9x3�޴��1WX��Ԥ�:�rt|�J�w�qY����	-��0@���1��`M��� �i�'�v�i-�QiJĩ��k�97H���ɂ m�B��7�i�Y.gU��3 A$�������u�;J��m��S&�����e�\pB̊����a�fB3�~���1�Ǧ[w��%0rg��v��j��YF���ء�7���:�����|�U�{������v��-9&��Ē�@v��k�?�Ή	$���3�m�S���� �k����av�>��TW�`��P �<��F\�����~�5:�Z��0�A<�㨎�o�*Wbi|��>�n����ZJ��3�W��U%c��f��������p$�Բ[Ӄ�pT��9ʹ���{�B�� �(��ka`���6}@����R�1���jmL#�Cb-�ԭ�b.�/;��F	��ZQN� �I�c�9h,
[�-=7cdU�(�h7aı�H��@\!�|� �|D���z	\�ag�##$i�9i��^s�dWg���ԅ���}o�/�
_�f�S��7�TC*ݏ<�t�)���XT�.&;�E#��B�E�$!�{�g����+\0DY'JǬ���:v/pw��p$�
��w�ߋ���:�BA�jAAA_�:M�@@��A@@��iT+��$z|@ �L�H�5 C����u��3q��^�Ȓ�
\���ȥ�zT����LiaX�*Z� pږ�*��$L�%�%.ۛ'q�Ҙ�1գƩ�_��+lo��kyPxQy��v�,f��	csd*�m�t.<�� Y�8�&]��5.p�kܮyB�E�N?�y�?��쩆�nQ�nY����|�4܋�J5����O�M	�dxbt��fm���X�AF�6(�٢�`@�t9s!�<��������t����q�^K��q8ŬY쭘�����=�Q�h˖Ϧ���A+3@LӠ�=�^��2�c��nV!�\p�q1h/D��f�k�
��N�G!l�' ����L��%��ΒF+Hk�^��~x+0�u\�H�ڼ�ݐ��A����p����v$F`:GgX����ڂ`����Rn>(��]u!c��vG� !@ 0���)���EńI�>���:ZvPM$�ء0x�+����mcy�ŉ�D��L�\����j���V�K'Ռ���Y�B[<�	����]�.aW�;z��8���v���g�U�C����>�¢˶�̊��0	b��gc��k,�<�v���P=��x��lؓӢ�x
v�&�)�y�֑ӏ�1ޣ���9O��!^ �7�C�̇���jvZ��u�si��0}����]f&�b^ۺ���&	��	�j� 2i 1)/�Y�D*-Tt��T�\;��׻��=0贘\`sF�@[�^�\�s��72�ؠܒ��V��~w��3N��4�ZCx~Bsk��UK�	/��8����Η��#�����3F�ι�;$�Z�z4h�	���?����)mWc|#$)b9*`h�LָQϙW���^JReUM�#`7��lH D�0,5�05� �mݜkN�@-��l���77��|S] q?�p�ć�=��������Ũƅ�
��F��/�V�"��;���?8 V���R�P���������oR��n*ՏpY ��6U�I��7S�^7��*͛�<G�CAVA�X<�ޙ7� <0�7dX��#|S�f�*m'�dq �ơZ��e�_T����<�Z�8�~.,\���5{��HB��bta��A"�T����[se>���U�ED�|���{�P ���q�/z�J�wE��~�zb��ImV ����L�r��$�d�'��~g��9mz~S�Z�V�Z�6�P �!���ʂ�/l�z��A\�����B���e���4<�c�܂Әϙ/��{��3.Z��\�T�x�8��eI8�.S����`C[*	sEF-�4�Խ�b#�D(�
�l� �xD��1J4T�F|"_
�o�|���5U��H�G[�sh1�i#v��kǉ����Om�Q��n�Zi��i�N2p����ڈ�S����|FX؀Gb֝�;�;��oG͐����%o$q������uL�n�M�X�`��o�����*��}�Z�b�I�Ř�lm��7F���G����$�q��{��Y���-�N��L���0޵�$֜��àG}���c���\T�B�H!�LG{�`�$L8��H�H)Xus��3�=��_���>w�H.��;�e���V�$ȁܮ���'(�F�~ʷ�G�^_�k��:��t�3��X\�=c
�se�cK/1t�'��*��2�A�'CbRИ��,n���u���V����9��#</�uQLx�TGV.�$q7F�Ć����ry���ڻDi��C�*b�-���G �PL*Ź��$w�S�U�� ~5h�%�1�վȀ�w��㢧d�s��!]'nNfb��u	���Z�)�@����`�e�����F����P���M�����}ތR=�Y޸P�t��/�X>���Դ56�A�]�i�d��47Y�1u�����M�e�2^�=��ŀ6�u�dG�+��>�(	'�a���\l��<^�fr�����o����F,ԟo�S�b}�������v2�~@��}�JQ�Ɵ q�fV��?*�w�m'�E�_�T&GE#L�ꪜ&݄`+E�s4(4�P�~�{�G��x>����e��>A,����Q��q�z�*�,��Ow%m|L�}_���O_�0�ۅQ�Ҍ�0dq��2������C�:R���#���8$��^T-U��������s3����I�@3&�S�&�
������ی	���A�X�-�V�x��!`�E8[�������=#t<-9���i:а�H�R9{~(1s�Ai��^'�_+����X�/�B�N?�`-.��>@�w�
��s}�����&��]����0�+i��a'p+����f/@�n2Kxw�hb����"�HH�;�*�h�e]�h�`ۛ7����(�;�wH�Y{��H�nӄ���qV?e���c����$^�;�*F���GsZ`��5{>`~��]8g9ҏ.U��
�s�Gu:�iPf�Y��, GS�S,��! œ�$Q4j���|Trm����<���5����w������TIB�/����.E%.܎K��E��Hw������5�N?�!8��w�i���j���iq����oĶ�M#4��<�Ԋc��hj���mc<FG��Fn��H�ʭ*o����[�Ã^��T����F��߅(4F��V��M�_�}C�^k���Xh���r�F��W^�\�W锪֨��fA-A�����ld�S�xD���wa_�V������S%`7T�k�z���Pn�"��遘���9�HU�J �[� �>��r�s�� >Ʋ"p	&�	 =9� �PP��bB��r=u{�'ו@ᡩr���Sr�I��щ�T���G8U��5�E��@���y,�<��Z��ibk�2�$"u��Ad��ݺ�|��f�2�>8f��Wb� :�N�W�z�'��	H��P�3��V��.����&~!#�����0!�!Jj-gz#2�R��*��Y�2��N�՟2�hZs����S�.+��3i��+wAS�$C&3���U*�����3	����
��˷+0����9N8��UЌo��._���NՅ��~�qТ�2}�V2�P�t|�}.6�I�2�e�b ֗exr�]!q��u{�:��$��� �2E�b9��+������$��wYD�F�]�o�Nh�����gH~�0&�<,N�S�A�~�>A���>��Nt����h��d���d�~F��s���T_����li����K��e"CP�e�a#'w�7Q�H�[T���#HZ�Su�a�.�t�k� �p�P��X�*k�j��?�]���[�6��\��@��z�`� B�a}�?�����Q?D������C�JH�D���)�
|�!uA#2V�V���\���|�{�R�Tze*���Y�*��!E�}�,�b~�Lw�q���cꎌ���_J[r���"s��8�I2r��6�u��F�(�0%���b&�{pc����a����(����fXX�.v��h߉p�Btl ��X�-+�֏Ȥ����&�V�I��U8�8�{��n��qd��0*G{j��}���)��-���{�5�jݙ��H��td�
iUI_��Wv������I�����'�H����(�q�� `F���O�Ud�1�}�@�L��p�^���?�J��*��Q�ʹ��k[�73���K���j�c��'�0>�#7 tF��"RI���ʔl�p���>F� þD=7�@vF�$�	"��u"Xֽ``g"k۹�����g��@R]	��I	
��.$�t��5Js"̄}�;�V�l��s���;~.�!q#�c�k�Z��}vM<���w���1B�5 x�P� �����~`R1-��tq�i�$�DsT��-$�(��n۬��8k��rw8���������0�%\�I�5@���@�1	J�R�y r�&M�"Q��yR�n(C�G !N��1���eY��Q��Û���z���tŅ���m�]?ړ'捹L�-�C�&iА��ѭ�M�W����U'R%�U)�*7.Fx��<��=�W�_���K�gS������qA/ .|�y�3;�,��Y<��q�ZP�5�1T�P��D�hT�_�W��pI��č�뇐��A��8�Rg��b���v�������[\����2�"Y$,��:���۷���Kk�Au�f�u�:1��p"�U�Aڻk��!���Q��,K�ɪ���D�2���
�T{�ָ?"5�j����X���� 5Z�F/���׋� G��txyxՙ0r7V0�x#~1>ИX��p%6LK�8�K�2���c��y�!���U�} �=�`čZp�xn����4=ok}ǝA.
{��.=U?$w/Q��jt��TCR��k�<���v8��+��T��}��.t�E��1�ꎗ��� hG�g��gֽ�#�#a�B��a��I�����(Q41�4I��J]�#8��o�����˻�K~�|'�F�UŎ˰�x�&�!�t>ap�/1�Ǐy9w��/3F�l���3�*e�p����0۰��i�[[�7,��e�Q<�Z/�VJ�pk9R�<I�A��F�#���z����꡼�*_[�}�4�+��R��NAW�p�0�i��3�MI�D0�v�\�,^�����H�I�K?e:�3"J��+�I.ɀ"��t���}=�Պ�69�p��j�I��8�81�3����7���Π�ôe�z���ءZE0`�_a��>B���Z��[�iI����c���\`f�7���V܅�h,'7�Ę W�)�DI��H���MH��l�7L�Ed��\u�tp�y����`9	͏
p��u���e�7�h�dtÔu~�='�ji�f]�r�<�,iEV�T)o��:߯��Z����W�V��ZCY����5��*�~�� H�
���DN��mJj��g��M���� S��{��w�hju�ʹ��DO|$�uo�;�9KF0`��F4�0��6:��LwRG�U��+%��H¸�:�6}�Y��=I���Pkc��k	����'2"x�<��aIu��2��Β��ˌ
�Ĳ�ԟ�o�Ϸ֗�^<�w���c�1X�f T�7a�I�VWd�q�M��,��<�P��ğM�%���.���H3�EV9�o�����ЊR>#'����*Ĩͨ���7GK�{���_�$b��ÜK@�D�ݤK�Π��
aB&o�f �j0�l6���8��m^o?Ӑ��{�ođ]6��s���4a���8"6�I
5Ű�x��Ş~G�Dv���ͦӚ@@R����xh�$��uy��Ҁ������nb�N-`��xjs�M-���Z\}��P��i:������������5�.���e��F���
�K��'*��U��q��Smycb /�s��#�����2�Y~���vF}��::G-��A�M$�>�����r��Z��E��Q��P0UU.�Ry��x iK�DUv@2YLB���������V��@�dAj�Ӑ�������:Þ�	��݋S�) �=�4I��\/RڴC�h�8��X@��P��K���i�g��7|���q�O������9]�3a?W�W��Ê�u��ׯ�-M�v���t����q;��~}
K�!�m�d��@��o3E_�p�ngWx=y�r�K`tup�2Dh�qF��+�E�Gh�9���s��������	UЊ��Є(�	����u �kX����� 	�C%g�����qա�Sy��u�/�t�H�a��Ϡ�T��*r���� $����0,+?�똨l��L9e�P�� ��ZǄ��i�	b,hIׅjeI��L�3�l�,�~�L��\�n���0�:V��3n�A����^Is!,
���D�����Z{I�� Z!��Y��i��M#~���~��~�0�UJ��<�����$<Ձ�s���צ�;@��A�q�Ƃ�S����\����n&���4I��5��������u���,R�7X���ܺ��TA��Ca�:�?;�^��.ֹ��ͳ�+��@��%�57e�^��W
?� `) T��(��/����B%p��f���D��@$�U� ���!�H��f�`*�h�DyhF�	��0F)E��$�� ��tv6c�>	����a۫�L2]!T 5/�V���b�!�-���֮3I��Tk$g��Suy����a�Pa �r^��i V<����%�!Q�|2�4搪��k�P�*Q����V��D���;/�z�������BL�ϱ� !b��6.`�_�<E#���X�(���uI��6s"\��P��ds���.�{m!�7���t$�j�Jo�l�]�XǶ���2w��
�H��U��0��Ga�X�����Z�e4�_\�/�e��x#u8�\F7+�yaL6g8M���j!A���6_mO9cc"�|��B+�+!��7���@��ع������~�b�8��+�:.�ߴ{� ��	5^�xI6�&��@�J/����
[^�F�(�"iv9줛��Uy��C�@H�^�z�aՕy�V�����O�",ڗz,���+�h)rn�c�iRAFa�L�89G쩯c7X�U���-곤�6�A���Z������3��s\Ű#�$3E��X,�j�����#.8W�%4�(���T7���}m�k�l�e��r�lZ}�� n|x���!Ɖ1%�1�R�2%�(3��+���b\sG�
�x��u�ؔ���+nn�g��pA�9BB��^��|�
��qGK�/+�h֣����W��-����8���Tŉ���_�3-��`����a�5yA�������B�<�jP[V��� ����{�:���O}��#��nB��2�th���JvM�;C�<Q�8�`��k.�cՌtj������v�?� q}Dݾ����H<�E�-n� ���ILv�9���"D��_И�Gx?�Q�Q����s̟4L>L��Q{�p��	t�d�Q�q���h�a�N�ξ�?6:��)ޅ_wo;�]���џ�&�9[�Ġ�[X��K�y@�|S`��~�4�<���Vwc�|�8[�( �yV��WyU�<c�)�3e�L?�>���K��/�q�mI�-�-���:�F�Pta���]�F��u�ɐ3l������O����G���A�\�ݒ�2 8��Mx���I�ӎT�\%k��Az_+ke7Q��$$%{�t���*��}6��@@�{�����	 ����a���#��dG	A�-���ix��DbBQf�V>���5�v�z��ǁ�uS����=�\��$��ۮ��.)l7���Ӣ�m���}m>� �   �%�G�Q�kSY�ȑ����F�ǋ��˟_��j��&W��Uj�0V>��G�0����竒�)��5�(��p�� ��:[��Wt���;��ȃM�[��V��zg9�Y<�0AA�[{o���@^/��.O��ݠ�cp��ԩ� �+�ȯɭ��� � ��q��<Σxv65�FQ�u�QL~BC�b��lr=�u���&Wr�z$K\EŨ0;���ar��0�:�C5��|;�`J[��&6��r=�€� �|�D��� ��a�F�?�S;�{���~��ÿ�Y�\�nԑK�R��6}�6	R��`�J��lJ�9���u�����j��^�zʚ���(�AB	M((�����~�RK��� ���0��o�:	8���+\/6�]|�P�!U��v���[y� �?��;yGE���畳[v}=�U��S ���Ly	t+TI�TI�JV�GẈv���_* �d��F��}�-�M88x$��t�� ��A@d�C��"�2z�Waoy=቏��<c\/"�*̏����b�3���hB?���B7E&p�h���VY�Td��P��o�� 	�H,�[��ʧ�\��-�wT_�|����l��9��	��5�����F��FୀO7"e��}ö����g�ſ�r�B��O��i�YtX��"-��2.Y�J,J��M�Q�Ia�A%�B%��"�OƌD#�M�i-	F"�� b��G�()�%^8��s�I3$��< ���%�)�16C��w�H��3
"?���B!�%���,՞M��S��V��0�k�$��1+��c\[UBgȜQp��K�k!���@Aǔ˚�}�>��;(���@1$����
ܯ��baVJ�h��cz!���ʾ&�iJ��Ǡ `�������E7x���n�)*��T�Ӭ��B���������?��h;���H��}�����<���9�A���7$[	D@^O7ƹYv���Xx�yf��;U�Y��lJR��G�H�:E�ny����(��<B"	��<c��H�J����46�Ԣ3�D������=C�&��a�!��I�L}.iD	�,=�P!D��SHgA�b�$:���]nF0��h�R$��~q��o݊�S��	 s��EV�/`���=� ���)��ѐ�o��(ҹ�lr4���%�8�<�y�t9o�u�>5�:j�bϻ�Ji���������O�O���%^�w3�W��}�5��)ZM㟮������u��B�p����y���/GHZ}�?��!K~E�&YU�ȑ���U��m�.�IG}������Z�׳�1�Ҟ����ܓk� !�$u�_��P�5m��Ǜ�����h��/�V C �A�w��w8+��QI���]��eC|�H+���c�xY�	P[&�^���2�,F���.��	�
�%���R`�N�6�K_p�,�9��r�PS[p�YY����,�B�\��"��+�=�fY����0����
�,l�#ͧy�v� ��	����U��u�����p�z h�+�����F�zk%G�P���Cs��AZ+ͺv����'pa��ͤ��V�Ϫ�j}�:���)��bR:U�Z�e��H�B�Pk���J��/ׇ�>K��HB-��OÇ�Nk�t�3J�; �<�XV�ҡ5�e T���+�sxťt��=ȴRڃY���	��ؒ�b}`�	T|��I|�`[kV�5 e�������r�w�Q`�9�L���C��\f�ҁ��*Rm�{�ko�Ȅ�1�p�؉+��UPx������ ��
�ZI���"۾���4E{�	ĩCg�T�w[�	Х] �r�A�pр7���̇������OQb� ��ُ�Y�L���ӕIs a�_����(�#�gp��w.�L�K2�Ec���s2%�� �& !�"�ǔ�Y�9�`	V���:z��C�M�|�2ҟ�T{������g3�;��e�GKJU� 㢔�3Lt;�<��Y2���Y��.�<�/\u�����) 8S
�,�O/�Y��6'�����э���N�=�9��	�w~�F��<�=웾����R�s��b�R��m�a���H"W���b�b�/ZR,�K
xة��d,
ta�����4�ng俫�D��@p~q7IA,�Tk×��#�
��b�Vg�}���I��h�����WPTRVQUSD��昋�GҼ`�'�t_� !2u�wB�(T$��[��3��������*h��p�%�<��F��g�1�A�J��e�}��w�yۃ����M����A��59��8���.����@LĂ�4v�ډ����#(�$E3,6�����g�YJ�3#iv<�P���No0�����p�����h��xA,׿��d�8U�RJ�hK��F��b�ٳ8��t�=^���d) #Q(,���
�J�����7��A�):�b,�wQh��d�Xmv�������5�GQ̋��kيUV����i�������q��Z�n�&� O �ȂB�}���mǮ=��`z��A�(�#.!�b��R�ht���p����>y,�jk�*I{,��D�PVQUS����MGWO���=��^o��&�f��V�6�v��;Pt���܅��r?��]�:���_��Ӳ����t��o��x}~�M5"�J+rU)N�Ͱ�.�/�����A��R��4�����ñNo0�����p���O��s�s����`��.�/���L�P���No0�����p�����h,�H #(�+I�'Kg��|��hU&y%k���V�����c0�'��|�\�7���p�N���v���}�?_>l[$ö	{�����	�m-�cխ)Ʋ*���)�U�>��X�"��V宍�/On�y��5�	e\H��e;��a'i�eU7m��4/��u?����������~`:����R�R���V���!��g�B*��d8O���b��@FP'�T:����R�R���V������x2����z���G�t�\o�����|�n����
�W����~�̏��hUP�B���~%R�\�������������o`hdlbjfna���Ni����|[E�ĥH� ~'���?�p����kum���[޳��ξj�X����4�L�
��>Xaor�:�_��	���U#"THe�>�`K����Πs��#l��B���⨵揵]�x;���q_������Q<r�X��NP����K"I�V�0-��M��$[��� �YX�OΪ�rα�d���:���	O�Nje����ě��:��/Z���;y����T#���Į��ON��Kl���%G�o��x���&�K��TjzN6�`06�L�1�җ0�5�����y��\��'0�F9}<\#��k�����#,ģ�iI88�k=�N��+���x���A���{�%kMf Rڋ׷݉ȲtC��TWVU�Ӯ^�vj�y����Z����S϶�\���q'�-�X���f�s���mu|ܫ�U�(�2P�Cvr�����j��duV�wc���]��������8�[W拼M����8$"����:O����L<��^��F���+�G�,�F[�}m�vL���Q˫/|��ゎ�'h*��y	��|���-�+�:�.
��@�UR�!�A�W0�PLUD}�sg�*ì�ت:	B.ܕt(LE\O���Mi<�����j6����m�F�eăǽ3��R!|�S5[� /	��Xa�A�gxƚy���ZA���an�Ҭ���[����Fu(�� T`ĜA;�3�t�;˝���}t��LW��g�Y��ӛz�-��b8���0X�28�"`$��7��v[H�]U�0�����.� 2.��ƺ�B�ʸ�J�� "L(�B*m�˫�L/΍[�bW��oտ&&<j-��̾�]�GIsw���=�t'�"�W]�J�r�My�Ӱpy�5}��;��R�(�k��̢h�3m+I����:�;�1��(؃� 	���p &   ����W�D(�"YaB�����P�	Nj�x�}t�HV���=��g��n��<\�Au[iy֑�z��	�G ��k<  |$К  �v%��+�ʸ�
hc݄s�ܒ�5#�e�&�5�W�^���ա_���^�:m�Wej��^H �0�����.� 2.��ƺ�"�ʸ�J��"L(�B*m��+�0�����.� 2.��ƺ�2�ʸ�J���"L(�B*m�˫ �0�����.� 2.��ƺ���7��?��5i�7�b�/���4L�� �T��h��1�C8ʥg��!f֧�.;:��13��x����ϗ�-ㅲ2��WP��(�>5���?�v�wY�	��gz|	�gVܺ�pѻ����8d�34�q)ۊ�~�)��[�=@ܻa����כ�77 �j�9f�b�_lQU�a�4�C�K����ڰi��9�E�J_E��� V���g����^�ȌUy ��ү�W������b�6��6����W���oxjn|<�����{v�f1GVA�	 9T]�v�E�_wnC��N��頧��DT�9��m!=�,ߧ"�pkeUų�?.� �^\i\J������e�_\D�^#�O <�^�M�V1�Fe�fn�/4�2��                                                                                                                                                                                                                                                                                   сF�o���ߒc-1_���Ҽ���o<T���@5,{[��#��#��K}��BT]d>tY1ǘ����Zg:,�R�u��KY_M���_�ʅ+��b}�G�;��/�/
�>%^
����!m�����_��;)�JΘ�h����9�J���~�B�o9����c3R�Hi\i��s�W�F��Ά>�z�}�>C��6F�T�ֿ����)�_��.�3�^��m.ڟ4��+���'�k[��,p��7"�]J[�U
*�YA�o3�X��5xlX�f�`<�6B�a�)��|su}~��kV_#�0��W�o���e���
6�)��� Xf����.�Ta�ŷ>A*�{��-��aO��Ѿ���h|���BK=�)D7Je�-�,����!G����)���8�W��Bx
��>�]��s}5-x�e�B�Rվ�f4��P���e�����K<���=�������T�.Tlـ+;���D��S��H:�4���n�E7Ս�M�K�~��p��-_��v�o����+T�3ܱ>h�	k|����k���JG%�=Z�{ڌBi��d��ӻ,�%����밚�	�Z�Z5]+Np���������� (!��*�0�N��^��������������4��C��u���0о_�i��Z�w?��rոj�(k����V���������,q! 
�e�y�-!���R�ji|΀׺Vǵ�p����58��4v��n��qV8����7��<]J�R���v�a�V��V��L1di79"p�Ҟ�p���MX���')gc���$�X��i_2bVb�hTJ�r$�Ԝ �GQ��OR�D+]�e���ex�pMͬE��E,�(�0�����Q˖W�دE4�����uV_��*�j}2!Z��Pi5_����bF�^q\����c(��Z{����<|{�,��e������4���G��l�C���ӶSOY��lMC�*�
�G5u�(��DT�����	����0?f�s+<�?;,քd�籡��6^]f�}6��/.3�B9��+V�䩽rl"IY��jB�yb��B�@�$�:٨�p�U���T��5LA��f�����B�]ɮ%lP�F�[�k����eFU�8�}l�zX.Wђ�)�ͦ%��J�m�
(���LdYS���w���@�Zc��P�v�9��
�#�QGW+=�2(A\��#Łl$�?d���:�j��Dh�w%��P��v�n�C9�{m�,��jE:c3*%�5�o6%�a~�V��Oh|�X��k[5��I9��2�Q��ѦL�4�.�G+��H�����az;�n�5�STe3d�7��
�.)�OH:=)� ���߈�"���!\F}Ya��kE+�e8��yMHo�ˮ�� �%��t��R���4���\����[zXM����f���#���k�e_V�#�>.Z5=���k�D����"�I���?|/������G:j���jz�5O�^F���q�dm�(dozKD@�Pc���H��/��#�BDXsyd)�,��	��'� ?�'�� 1X�^ۓ�}��JA�CZe�`��6^RA)
�hÛ�L��
��m*h�G4,��I(u�˘ӋU�^�2�C\֚���~�
����Q8�H<�G�ZK�����&�\�k��*�ʰD5�Hr �V�[�D�۳*b\�6N4eX�9+e��p;谂�jR-�
�9�0�%L�@�բ���c���ָ�Z"�ʚN�P a�^m-�p�8�5��2��c���=�S���ɢB ��Q]�hq�����[�H�[�����|���v&^��VTzG�cj?�����B1)T|�Q�A���}�W��	�7?��t!�^wk� �0�	�p�tK�Ey�7�
����l]�C�i����#�Q��im
2�	�R}�az���cYd�H)���]��,��}�౅&��4���r��̐�/���ds�{�;lh4��*�3�y��x�^K%w�pDs��J�Vr�j~�C���VFb���}�?�ϩ�H�<|�:o'���:��Wo���g�i�ʻsf�k�l��yuƣj����p�Zo��ī���ZtR�	���持
�c3��?����ڬ����0^!z��$l祛�ٹ��3O�IF�ြM���4�_)�
�G/��鬗؄9 �)��
BL���I'`?ٲ�t�}?�+ݍ�DJyNW,A\O���+�|Ǟ����v���W��|�ꗷ������ۿIx4��9����ܙk��t������@� �GH�M�>H����P���Ɲ��<��Qkm�Ʉ�'�t�wPs+��z�J�\x��i�����I���L&�}-"*5����-�<p�sio��a>l��)��9
Tn�+v9�~J�x�	��4
�p�H؍T��7r���i_}l�$���H��������"B����&��"�-p�M�>'ܙ�$�|�;\z�'�@JS<�"m��'L�o�)=#�Ŏ�ie����R�V��p����J�fCcqj��J�4B�"���fj3�J:��HO�}tt���]�Z�'��ɺ&���#���F5�8�t��^��a��d@M%d�&eKQ6{=��v�������w-�S0�f�m���gA��K�"_��I -'�/7Ky�a�po�:<'�"�B�ܲ�0]v3�'Q��Ey��Y�7�z����e���>B]uqO/R8'��a�sŖ�˩��>�qOKIh��J>8�9$;��oC�;,��o�Tϗž-����dmfQC�*-�yG�󶃙Qc���b9 $A{i{��X�4��F�g�����h��}��։!��v|�D��n��>��明Z�2�o6"j�-�ۦ{I��ڭ�����2����%p/��9�lz��:7�;]���w�ܢ(]F1@��&zq�/ Ӧf)Mz�f �mL��.�e�N�� �p�k��nH��]�'�*^�t�����،��˫�N��9ԛ�?5WS�u���o���diF,�2-�%t���҄÷#m���xA�{������gfR}&�u<F���c�N�zE�W�d�59�o�.�&l�_��,���5�����(^m�;�����L��L��ҷL״ӊ�[��)�&`;ҁ��-KHb�(����!�o���`��Y��P�å��B/UC�[��-K�5=��{$F��������9��D�d�C���ʥ	\A��G�
K��)��G����?|<�˧�������?�������d��X�QVg��ŗ˫뛯�n���t�q���������?c�wJ��#�����Mx~�݂��P�W���+1�� �	�(�H��U�3+ �M���|��3d��Ɂ��p��]zi'm6�l7�?\�~K��@�3,�	?�(�q�,_������R+'�G��T/_�QXAQ5�<����/_�P3Y߉�t���΍Ҩ�b].0y�4�V܋�^�y@���6"�j����,c@�H�Z���-�0�@�)�kǤ�/�T�iy�A���]ڳuz_
O�8�Jir��X���då~�8���!}��,՗�	��l9���7���Y�$�&sw��s��d �4b�4��c�=7T1`/�mxE�w):&z�N��R�%�}9��b4{�1�:���{J5F��z�^��1ZwKc�LRƘ�}�(�u��D�����x�}֬��<��Ӂx����������x����*�:�񨶿���h7�.-�4H��ZX�la��{�~-?4�L��5��G`�M��Z�P��ћ�Q�s��Q�� �?m7��Mu���=���Y0Ɗ>8�!ǉ��NӉ���t� ��p|�5��M9qW�SC�fR!������9^Zv�L��4�p��p�sM{f)pV�d~p`�=@& �-=]M��4�����M�2��(�ĠI��ChϢ����fmu�P@Nd8�g����)�6����E1�ȍײ�;w��$�"òMӜ���?�D~d�sۚ9^�yO��0�f���n`��c9�!�<2���4ϵ�g�(��q|ϳ���$��M#˄�n`{��<�H�H���;��>�J���t}����3�d9�9��m�
�簒�F�=���L��({;��x�� �2�IR%�e�̜��i��un�ɔJ��86���}w�JA�É������ͬ̚aVr{=�s�`�deؖ�A0�If�=D"/2/p\s����H!�gnZ�9w�C ��w\w>|�9r�y ߺs�B{?F� 2<�̟C!�Ȟy�������Z��7��C"е��x���s���=�L{����4��4�x��)z�GI4�Y��ܝ)���4Mk;�fH��A"�� �I|���a�ac�9}�S�2-۞�N`ϝA���gu
�
Ț;�阝�4��{��9a�S�/ge��ߛ�\s�(NJХɿi�򦶑%�?����,�%#\Ɇ$��#ɒ�����eC0���6��g�3��H	G�T����s���Cf�t-�	l�}$M�Ф��L�M�ʉ����l�+���	.��)�� �x,M`"�-h�,��4!����
��ǎ��:MӶaqh>�$�5a����I��]XYt�����<X�;�S4s`;����c)U�-��-�vF��X��?9�Guz�)���T�tY�c9��L�*i�n�3l�|璘�c2�D�ro�S���Aڅ]�WδW���h�;����(یئ�t,˴��JGF	}��v�q�F���(�|�j^6Z�S(D�@X1�'PM4�m۶�|���Ô�s0�Ǐ ���5�3�@ <�6-����}
}���U�l�����R���mm��(����7�˯���&tY�i:��+�aꔛ���� բb�A��5�/Ȩ��4T>-Y4:�'����^��X�t����׏j�ԝU&����Em�@�r+�=ĨW�3uS��p}Ǔ*ˀ��W��K1��!�T�r�GV^B�.���b���h�B���Q����͔�uւ�H軮��%'��F#����疩�iTx ��fK��iVj�-b����ՙ���M��X�Z�nK��7��A�]IR�P��v�f�o	ѶM�s�c�o���B(�-�"��0��&}H��o����Bi޲��<
��	Wi@T���,}:�;�P.y'G�Z(��}����g���A93��i��l�Q\�r;�Y��+�&���Gw;;�N��n��H\�:�!L��'�}į����OA0G� ��a�U��,+d|�����ǲ,�9�+yrt������y�r�1�mB��D�)	��c�g�W2�<�~�ȇ1����(؏�&?%�9Lh��}�PU��(��!��O~&ʩ��0m
2e!� ��uZ�;���e����P�F�J{Wʅ��2��
�"��9!��߅�� Q��G�n���w�:p�y��bRek�}�O1��o-�`�/%QկD� �?�A��߾Ń���*���������8���}�Zp�e"t��cX�{��F�$B�L�i��p|��q�#u�{�ï����Gqה�F�j����<����N���M�#��*D��
]\E�y�2ZUO	Sfrxi��r��;�n,Ӎ(� o��p� ��+Ç����!��L��)틈��R�@=����s�
�q�D���� �A���Bb��������
��/�+^�w��]i��
�(FHTtLiˈ;SC��wѓ=�r��?��O���)�U?EQA��O�R��'}�L�xS/H]����u���4A�V����E���
��������bL����U��A�������� 5!jF�%n�A��p�ORJ��B���t/�H���UV<L��HL���L	e}��xq\�8*l�<T�^N����y��SY8�H�ע]i&V���L�7�`E�����ӡ�Fq�5O0|�N��KJ�&m��$�@q������L+��#���e(�{L_�K�v�a3r������~�W�q�9� ��.�����"��Np4��*��s�#��N� ���ص}��I[��7�@�����z���*�2���b�8F�YL.�$��XE++C6��*J�!wۻDЗ�}�[i����Ӏ���T5�"�{�~OKzc��( ~3��}&��7�U!� ҩ=��3�~�"k
MF!e�OQ��>��(ϰd�Yn8;�/*d��_V�(�'ʾ�(OU�O(w�c�Gm�܄5��El�L$�AoOY͢ U��Jk�d>��5=���1���,�B=O���t���gc@Q	�G**�������D=	�yO@3��I�9����jx�^`�AP=�:��$��-���s���(d����:[�.���ۆ|�K,�p	�"�S��,!�KE=�yp	��x�y����E�7o��ͺ��a����-�� 62�@�x͚���_8?j�~�0�U���|�-`�k�T�#���)s��2a�GF�����T������}@��¹���>�̣��}�'�6TJ�B(���_�ˏ:_��0���<�cÒ=	��J�N��lC(T��vU�z����E��^���R����~��R��t?g&Ωb�qf$h�b����X<��%� t����e2��K�(.>|��~O�y�Ȑ>h���)���,�F�#3�h��ndH|�s����F��J���apj��H�RVV3��R��8Xj�&�h�&hQu�~���[E
ᦥ��R�uI�N`S��R�����N���?�喝���Ra+S��)�'��q��x|)8G̍�[�!��(E�pQ���G��Fg�;�PWv'JW���pt4ڒ;���$�0	�"w�ãiM�wk��c�-�dy(�SҖ��(x� 
�$���8���J֤r:�4*�����.?WP�>)-��]!G��=s��x�:'ɝ'٩r9��%>�����d>��j3�������(([>Z��jB#��n9Kv��Mr�Y�Ac}��P��8�lv����P�˖:n�t �� �.^P�����DS �M�����8�@�J/��Y"*'�T��t-�)A`E,,=1�:�m-5�P��T�6�9��59[^�@�� �.�v|T@v'Tbᔶu���X{Pa!J��%�:�}'ƃU{��I�\S���?�� 'y#��a
A!h�a'��5 C-�ѩ!������(���38�.0� *\�NR���%P4�a��S��~Hu^r���t���K.ff_� w�i�P���k�h˥�1�Y�S�V�8�(�g)�Qi��
@I$�M~P㑒�H��r���*�}�gu�g8�$�e��G V�y�t��E�=����h/�ˡɇ7< =�yv��/�Y���ܕ��+���x�C"�Y=yd/OYO��P�IU���q�$�26t���QN��ӴS��P%�,�����٫�`m_��%L�����`�8�+���.�1�_�e����g78[Ǵ�4�,��$��1B�c��p��o�׿v�_�����-E�T�ݿ��}����:�5i��/��+ҟ>or���p
sX:��:����%U:�G��mЀ	�0�G�~���~X�Q!n_���z8ƀM�/���F"�ԉ�v�ݠ�&���= I�풒w��'���5uI�u��zX�O�SW�>�n��O˴L��e^�n�)��kĪ[���Ԑ`G3ӄ~�G3+�����k�
1r�/!p��'kǛ_1D��R��I.&w)�{q�gB�L�1�n���-k�϶��I%��Q =�������n��o߼��󙴳�{���&���53�q���Ԟ�l{4���*�������hL�Smjië~���T�LN���,��^�[��p	��;i�6[�+[�-�����bښ�uCsܺf�uK��a����iOZ�c��ֲ�:8+��t�&ĩ8�ނX�Wq5מ8�c�45Èm�p!������M�2\�h�J�|�,�y˱h�L�b���y6���5τ�y͊aczG��A��_s_��F�4��_a��C�Ccl�j�Ǉ 	$��@V���ݍ�x!!����"���60s����n��R�$��JU��h#�j� ��g [�>|D�jO6+Яz��S���Zc��� p�O� l�����ڳǕ�H�4հ�
�P�����G%��1����x�������Ǖ����'�0��1d�O/&jz�!דZ��l�	f(�+��G�����3D���ʳ�ӧ�'��GX� ������G
{��'������hlz �����z�Y�1� �lo>�&��ƳڳM� �6*��ǘ��46�'A��l��>%�V2�� �& �lt>�|�po�2!����n-�	�������u�7P�_۠�I�����j8Lz����_�7��e��-0g��Ut=�s�A&}�#)W�$3/.#QC
p�/�����2*�t^�?��}Տ6��$��Ǉ�����lG$���h�3ٔe�G]"Q]2�x���Q�5��j�0��j ��o�GL\X�2��ƴP<���3�}(�c���z�j�݆�y�K�{��[��;����l����a�h�]*���o~zQ��oaDЯa�h���G�D��
�0�,����*ỳ Ѓ������%�Y������s&4 ��*�5ё����m5R�.�zI���Y�h�Iv�W������^jd�ok�?�~,�xe9��Ϟ�-���� �`�>؋z��� �K���~6�q4N/�઼���P;��X'Ѹ?��Y����N0c������r���,�-�b�G\��t���#wZ���/o�������dE~�KN^����E�~�>�;4��U�iQVW�����0����1�~��z�A� U�$��؋�qZ���O1�%k���N����Sao4Fk��^�D�Fe�~�?����?��a4���j8�ݡ���`�H � �7�r��NL*�.Z���P�l��u��4���%��G͂f	�H��n��ʨ� �'����a�x��q�l�E�}��ݥ�lA�����B��)X���sA��A��2W�U4��B�Uk[���"�S̖0�\��F�t�5�'����D�ߚ2@�;C��
�_��~z���C 4�K�+М ��%���)Ol��s����٤��I��o� M��I5.��(W�h�����oP�)PC1��?l�����u��#.1ě� ��+��uYx�� a�V���rV�D|�.�˶_���}����y~��5נ�N���l)��*��x%�hU[��6K�8<���s�7F��>'�(BG�#B���[]��)v�P��0��r�X�D?�k� ��h�� *f@g�ձ��&}�4g����_F�s�D�3J(I�T��f�#�Y �j���"�7|G��e�0�plB]J,��E���}������7����� 	�T�G�NE���&~u��G�Rh���j���dT*��?�Ne�2�E����_�^
���|�>7���j�ES��sHy���e��t��_�$�ɀ�>a��CنH8A2�m�`�T�F���E� dgA'��8�LQ~_r� �of 	�y�}�LƁ�.ɉ���-��|�V;��S�)��t3�<C����Z�v��]:�~+lW���Sf����am��/�1����b����W�AC(�nn������W���W�L%5�2ȼ"d*I��A�-���!3��{�_���SO>q� eO<���GsNx�)O�^�|s�TB������|@��Ewe�}�ZG�א��}������x��Xs�9r�H��p��|ll����§,?S`K������=!�iJ��}|#��G�(��h��Ʋ���V�u��+0c�F�D ��� �r(.~ȫ-H�����Bt}�aO��9���\G�Uj�����8�ԭO9{	�ڔ�U~�E?�f���Pr)l���Y��� ������G%7��f�l�~��>������h��49s�u��x@T6_=AE�܉@'�(Q>P�*�&��a�H_ʏ>�QK���%��>A��ȝ����ACժ�n�m��|��2'�h� -6����t�&�Pt��y#� �_[#���e&w�u�v�(C?Z�"�y���q�Y]�h�/��
����\��>m������ L��R����!�:��h��@�M�Zߐb�U7�[��T�<�q�f6p��V�>:����qq�x�<a<���ê��z$r�LI�P��0a�e�C��q�Tr�T�栁��"��F�J��w�����'/�	���F��C��ɝ9%���щD��B1��l�bZ
���-��ҧt�K��V��0��F�vi,��SO�鵍�j��Q.e��+1��@4�WeAϯ �Dʥ;覝���s;�cF����SէE�WH#;7�p8�B��[d���ȶ~�����?��K�/���`���G��n��}�aP=����.����G��bќ���Ki�!m�ۖ^,D/Zk}����<����Ѐ�����(�$Qh;�k{�S�����;++]�7ZM�h7/�N��e�̥Ή�l��^Y�	s_�E@�gg�p��As��ۥ�t�Ǔ�e7}��Ԅm/�8���˘�~n�Sp������.�W������&%P,
�.�|/����?�N��hԌL*ߢ��1O]k8�V��Ӊs;<�4�ab���B��,��&�1S�ؙ8w!S�����\�ŷ���p�U�NK�W��>�U[�@ِ�L#vvr�v�g����B�2.�e׿��^|F��fe}m�p�<�ЩL�Je����8 �#q"��:�K8߅-���)om���a~̺1f���6:����M�(--���b4]uD�ȑ=�����n?��%�G�8�:k���b�31�(%��d�,�"�F�x�ptG���&	�B^L��K��a��I'��N�[��q�^ֵ�0�ߍQD�Z���/�4f<������8��L�Ғ�K��Ru��[@�2� �s\Z�V��A�,��<�KǅInZcH��UD��$�����Y���p�����	+���9Ҟ�aA�L�O��,�Aq�yj�9�G�Π����*��*h�-�9����!
.�Ng�v���b� �9�Jp3�����ܙ���n٭r�qDg�YO�zǰ`�FW�9�0�n���.;i@�m��}����1���*�h?[?.MdȽ]��vS�gUO�p_J�$y_��t;#\wt�wr˰}�C� �ĮH|9�O��;�[?�'l�q����5-vk +-�l4:��x:C}�W�@�#mG �L'*�?qT�q�c"n��כv�A�H >(J�&�d� <���]/���9iQБ�w'��ۑ�u�?�K�ϟ����Y��'۝O�훀_1���.	��W����Nԓ��3;a����{����\�;W��;��w�̞k7�.ktSi�e����Y��o�G�(e^��hk/"� o�
��= �w�;L���_�QxEO�Ra��P��_���i�� �N?A�ظ���R<b�6K�6�Rl��Q��]�C��@��o	�S�)����b��g�F��_�↨������f�g�j�����bqi��wo�����k�� � '����M������˘����݉���}��PIx����<��Wx�0�R��l�p���,�8���i�#��M�5�"�u�9Mk�Z�9�m(;u����.ɴ��Z�U�zhvp���I󢗘y��"V�+l�յ��S�U���l�dò��$ou5�Q��^��\'*m �R��ά����w��zВ���� <x�kk*�6F	]i����!q�|�����6�5��X��u _��:��Sz�����2îa��6���f_fp`�V?z��X�4��oCUvD�@��]���6��8�C�P��55,���ڂe����Ⱦ�2��4ֆ܉��.��j�\ʨ%�lФ��Uӿ��,Pڳ�)s�u�w�k�՟��>��K#>[w���UO�e��i�`��������e��RaD*WC%�^���1��uˏ��*8��̪��[���^�]ם��鿲{�y5RU���4k_gz%���z⼃�k�j�Fsƣ�⩞F�g�k�gm����������U��S ���a�������&�`�(��@j���|���>����g7u�]����|?�O�����"&��e����w|?��u�;_�߯1���(��4A����|�O|#Z�&_���yS�0�Ly�k�77o����P=�v-lF�w�����z�7-}��.v,���#H��{Q��U���PR�X�A�U�����I{��=/���^��Dk����>~W���V�bgI$?#���|d�~�m`F��	9k�nt����:oT�y:���Į\TĄ����d��3���(%����ږd�q���T��E)s��v9�qגe<U`E����8�_Ʀh��d� ��_�������_�Џ�١���}y!��*�(:��h��]YL���o0��^�tuk�n5������,̋�бϞ����wR�7�_�^����@�
\/泰�r��R��6��G�f3����Pp-c��z�̂�����VT��׍�KxSN J���]����kT��Y�u>�Z���|Us.Ĉ��C�U��|<�~	��W�w�2��S���֚�*w����(j����}�������wkU/k|�˄��Vo���v8Lu㏅����)qA��Y����p6�,��yw��� �o�N� ��k�e5�n.����۫�	��j[L�������8�]'N���/��#P��LA{�ND�^�]R�V�ӨJ���eO,��(�DP\�Wj-��	d�|�ȟpH�?o���!��(�l1l������a��%������\�?�- G}�,uE��Ӎ�&%K�$�Gf�Y�jޱli)��	�kȶ��9s�}��)�ɀV���B������wQ� ��s�7�<��u����h�u��[9��& �
���q�RM[%<�XV�1(�b�;��t�IA0Xn�QffA&'�3� �'9Z*ZF�|�[�c��`<4;�ug&����\�-~�P��j��a ���3&L<�^�_��c�.7��K�h̆�'�r�+����j�'���4B���hx���4�K�j�A�ƴo��r��v�f���g�V�b�f!_�x��¢e�'i$ú��=��p����4Ћ�SП���kA�1���X[P�2��9�!h5>̝QI���IǢ0��-��MYr ����5P��}��	�ü�q?�:�G��O�ʶ��cF��I٤�i4�������p�^GKL� � ���@�A�?|�)�������M�����I�do���1}�L���	ݰ_���f��D��Բ&��t-����JXJb��k�j�8T�����NS�q���x4�,N�άt�\��
�A}
��v��W9��I2wg��e���x�Eu��������ꈩ.l+1�)�IN� �T��C�'��HN�3�Q��Dt�.LG�S��GeY��	v��-u՗���2gں\VV2���B�L��e����7�E��<����,6��k�=M��@|���� ]��%�8�h�۬zh�ԯ��7��m(��2��4�����j0�~��|�n{����<�Ј�n[6
��[O����'[���Z2LBsVB�	���T�u�Xp�G^��zB��'i28O�?#��s���(��Lx��wN�
��.�Z�����|��������B��e4ƛ�Z4��iz7�q��M��0r�`R�%Ąz#z�2q�o��&�����^���A{ؽ���o���X�а���Shk ��7IL��Os��Ȧ��rW���[��軹^k���c�\q��$D�Ny�����ٟ�����U�$����#�SՁ՚mb����[�5`�`��ǲ�ut�F�q��|����p�k��d�1�qS���;�Z��F"7���6�(5N{��!6�5o4�U�>�r�@U��LW�e����t8�����	�q�Fx-�p��3[H��L�c2\m�SGd��q3�D���:�tT�D&?|�i��:HL">}��`�_���!�*��<�H��I�����N�W 0�hx�4�����0?���8�E��Ey�i%��f��uW4
}6�ޏP�u��w�Z�H�Q�<x��$����(�wb�#�N��0��B�{IW�3!'i��:HI��f�>�y*}�p;�ֺx �<���A�$�7%*�(���q�9_��[��@� ��B�s��˯��$��'ҭ�N��b`^��{
�;��o�&7�~�D*�8�Am�W��gR�c�����
,vELN =��H|��l���~��eK�1U?���}Ȍ~.�'f�:ząNa�\��G"H��!A�19T�&���BJ�)��ZM�P����h�3A-�d��`0@(���fyv���?��j�O$�\1h3��@�7GIW��	l��t����G4ɍhv!���ԫj �8��,�{FVN��)fA�^�� ���f�7��%}�c�)A��tl)YF*�J��g��[�d����2��]f�m\&�Z	�L�e�_[S�!N}y�@W� ح�,�;�Ywp�e`��6'3A�i7ZB�xִQ(/�?t/�.�(1#^����tFw�Q��&*d�LBVFw��sn��2�6)��\#Κ ŕs�����9��D	b���0�i#�)c<^�_�������ˣ���������󣽭�ݍ��w;�<�.L��͝�������5�R�d@�P�T�+�lCA���-Ja�b��ϟ���R>8�,��2R���QA$����9��h�x��/�����ݧ��wv�!��
������!3�,��4!��A���<�0���5�1�H)$@7h!Z�<��ZK����ў��x��0G���<�R^������(&�&�������v���r�v[�f6@�.ڋ
�%3ⵦ�p��p-ĥ�@uj�d�a��
���jQ(W��X��Qr�lA��0�C��G�ڬF��D5�e�(�Ao.>o���eO%�8/^K2�ٖ�%l9�'y���y����Ά�ɓ���b�ءY���ުb�n��N�;�5ߑ��>��P�T�p��ywG9�0ܮlf��\��s^�z�P��C�~��Ӂ������p��؍��rX|���{�9�LEa&�����}V^�I�	3�pj�ͳ�I�'�����b�Ñ�p5x?�8��*�|�k��qAȀ����ǽ_(��k�Q�U*�jL��=m{wu���L� ��y8�b���!�wa,Dl��Ϗ�[)�DQ��[�}T�>���[�*�O���8(��bp?�
�g�^��WB��H7B�	n�s'�]�rk�_
v��L�S%��D��Ӊd=/�ͳF=o
�_J��IH�e��x�^��\����C#���y�n��m���zkw���|[��yH9���,̹������z�EY�(����^Gʮg�c9ⱄ5XƲ������;��SV�J��R��2�NO���l��BEP��l#_ ������Jyv8�Yv`�x"G��	U'"�%��>�v�:��>�+v]��f[Gy)��,�h���j卧�U��d˅OG�y�r��qn�i��4tw�EԊ��Z����):��K�gq�چۢ�X8�X�n2�:���t��	�6������Q�W��&�W;{�g��9�y�b�~88&a����+��s�N���tp9�-8�]��t)�@��'�Z�N���ȃh�}<��
�R����K�Kt7Y�z�Di�l� ��>R���Yn(_N^�*:���'*�^�*��:&'@����Ɖ{xoȹ���!?TN ��|`ͅY͕��s�t�vrK�������pr��y 
D�z5K�M��օ�z]6�=��D�֭��j��ӽ����{�´�B���'1L��U �ʴ�p�δ�ege�%�>v��ֳAD=�U����D�]iɫzD�v��[��V���9t\�Uk�H�$���c�����p��c���>�S�^,��fwsE5(�_�����M^�V]y�8�g�}'��z�>ϣ�F;GG��+�4T�h#P՜+�:�xy�B���yh�in���c:�ၛ���m�&/<������9�,�.$��3��[�s�?!�Jҍ�Kx�%�mͦ��8�M�7hݒ|��K���M��j���*�m_��u!��We�F�,�2	ZJ _�S���4]C`?�.S�2��$�նjy�k���%��ګX���w,	H��a����>V�6	�%��Ɣ��Rh�#�耀�ٸ�L�%���qzA�����9��ѻ����V|�3��Ņ�1�˨�rTf��	�U��n�i�'.ڋ���hj�U�j�}6�U4�o�\ԁ�[4���X?Z (_kX�3Ȅ^�t4�.sF���n���~s 	4��8�9= F�V��pԩ%m�ө�|vOml>�|��6ŗ,��[/�'B��s� �=���==���������Å���zty/��·Y �j��=o�P�c�V�\�;��-6]G��0>Hd��xqf.���H�p}	A�����cٷ[#w�Z3��.�A��~���l�������UO1��v��q+����g=�XY�&R����+Sf���-w��z�-����qY�ڶn\���}�Aݲ[������$�@{t���U��lɩ�e�|*���*F���M����28s��q��X��$lp�%b��8����]�^� �Ô��h�����pu��}������ e����������0����W��_��^T�wӌ\>E�l��AZ�>]��9ΌwF.a��ln �m��t�nW�YFi�(���Hvyx�1l0Fc��"�u��|&��;tg�[g0G~��vapq(0�= �nܧ �,ҞbךS�%�h�Lm��FB5A	�b�yZ8�-/W�3֫zy��L���훛��:a�w�B9t����\$�1LiԺ�"I>B�������ةfvƍ��m�L�0�z}SJ
Oh4�p�&JQou�N���@B���M��@+���U���� R�Yt�']�@(�&����.�Q��x�����e�߂Z`M�8:�Xx���cʟ@���p(��0U�G�Z��gm�O�e緋.tָ��9�!c��0��}\�5�i�}T�?w�л��ub.�u�M%S�,��î�o���C�;�jAƧOH{� ��M�A�{)�M� �����a=�	���;��T�������}�-Kg���oi�B���F�>R�A��5�s��ׁoo�@Ǜ���`��MbP{�t�h��y&����J33d���fn����?�F	]�V�Hף���_��C�||s�`��"�����S�H|���hX�0M�U;�����JBb��0�[z#2Mt�Š< �nu�yBa59�j�u}�rY��H��l+��t�����&^�~�����Ȟ��5���.:I�3����gM�����(��Îp�#*>��B'}�t�sN<<�A�a�D�˸C�8�y�h��ԋ@)*�r1aƻ�_�;w<�{GX��·�󙝾]c�0G�s���wԂ����z�P���b�8:S/K� �G�$Lj�ӣ|]�|]�Gu�.H��+�x��:�����g�鱶�1�[=ցA��'���'zB�$�%�H�
����;�}��*�N)����y�bd����eC�s�� �u<ģy�a��6�8NB�Mu}3��k�T�t�'ѫ!������S�a*�#�yE��!��St��B�$�=R�/��E��?|)����)�{�hlJ�Ѝ��r�n�]i�D�]�ĸ�8��;�̜�������0���}-"��Q_d
�]�ۆ o����.WE5�WI}4V��`���{�n9
�	�	$, Z
8��;�aFy!���\^�ا��8��rM�R~��ܡ2ڹ�JAo��v�z�&B���ɜ@��ɇ��R����.X�	K��w\#AK�܅�PںQ���pVl��w�g ���U�lC�w�7��Wb-���n-�gi1�0tjS��Á�h�i�#6oO�������t\KΠ�Lv�3Ly]c�PU
 ��Fs���(��
�9;�ƁH�	��H�`5��uwV}X*+��&�Y�� >S�b>'�A�ڲ�<�
2/CK@��T8�
���g܏��+6�����s#�!�["$��;�
�:b4:����8���f��5����굊<�����F=�'j�c2n�M���������w��kV��S�2��Y|�	�	����H�ӊl'��m����t_e�Zw��V��5�1����������ׯN'�T�3��9��܀�^&����9�������-���Vt��4�6�'h>���n������ځsD��Eu�����BW�^���0���[���!�C���f��<G�ve��`��ٽ�P�"�?Ϝ�
��B�K�_aG�C�����_\���:�/y�.1��X�v���l
�b���p�
d W�'R����7H������A��YLɇ�����ZFp@��A��͎�l�$x�6�N�� �B��7z�s
����Dba$�0v8� ���S�����m�Ha�>�����Y�>-7�77�ݗ��Y;1YK ����:�����YBv�ȥpO��-��vQ+K�;�+�e�4�t,Q�=�>�Ȟw��=�s�=#�[�Շ�_�*��#X�1�706uA��D[�	��x�;�.�ٜJO ɫ_P�q�r��s� s4�s��^�$��,���hS}=�/kUfZ���nQS��}f�,�������?�~���3wp�?9$v�mH�n��P8ك~��ܽ����lC%ۙ�o[�O�ϜCw�?T�k��-�.���Yj8o'Q1��<^�$^�X�b� �P��[f�7��_�p�gI��[.a����<�q�_\?��F�1��	���Dd���0	��+i�5�y�����*|���C���K�K8�=E��2����R})n������u
����=�M+qD�_�Dx��ܹ,=���ƾ7���L7�׾x�v��~Ƃ��/m�o��}�KzHJz|s5�y�Q��p�gX�N�?�t��¬ֹ����^E]�Bg8P��M���xC+Nůc�������U?�B6��JF�uIn�� ���3chy��Ŧ���s��u���xb$�U��;����ږKG�aw��B��֤��PU&X���a��Rq2���fK�`m���������������ʏ*'��J���� �H�Ă�ꆏ<��g+/��2VW}�c$�eadZ�`>7�A���NI��>E
��׷9��t���oBʛ�z����@w�B���"�KIB�h�,�L �#I�p�/34)G��	9��Bn�q�\A�]�6�edGsy��������E��w
5A����ț�q��򠱢]d�suc\ ��7̏:9�x$2R�Wuj;�0({��^��KF�FxJe� I-�w�{@W�Q5�=g�Y{�!�Ig��	0��`����4���n3�%F�Ey|��i�XY���~��l_�~�<B�iM�u1�:��v!IM򫼿��@ʋJ���o#;�1���͍���`�.UO���Q�ѵb�}-V����F+�g�������T�ܭ��!�Bl�^���h)��,̒��K2!�QT%���P�b�R����(����VDK������{����}�+W�� �(.�B���-	��[�.�2%[�JCɎ��:1��c0����������>���y;��-���k�}��mǰ5[���{�
�vz&�r�E%s����TZ��:�.e�`�NGx3Vi�'�_�ȃ�nfĥp� 1�'���ع/c!p.�4a�!V�#5��A1m��&Es���r�2�Vn���I��Y��S<{��rC,s�xF���m]�ctG�-o�&�VW��_n�.=��h�!h�&gW�t�A,#>:�@�'���f˚{I��V�o���[�x�/���<iH�K���R�	����;L��EXߨ��4L��!���	e9�on4?}ʉ{}4у�G�#:�l}���\yޮ��8{�	�ŻM�ᭁ-�-qG&|��/9|��ST�Ay�i�5@s*��^�w�|,�9�;~�:g��'�	�z���/a�rN��Z{I�T�a����#8ElDE`���R�Z�����Z
����]V�΄4�{�>�>TQ�K>l4b�DI�������q�������������� ![~sm.���{�Vn���Z���ѯ(�������b� �l�U�Y,Xڛ%������~R��K���gJ��er�,��w�����MQ�������y���R샢�-y��nDpM2! ����)a�m��|Q���s
sOJr�t�4t��5<�
�	���,�E��kU�?�떭�f]lc�������6��wP�i%Cpn�����fZ�Z���pik�ֲ�6Lf�EՂ�.h0�ч����E�a��(��r�r14��� �i��p�H��5E8�fY���t��<���YD ���㯢q��c�+ZvmX�X�A X���@�����ݥRS�SH�`PB�vxj1Ok���xGw
<���5��Ya���z7Y�J��>.[�	ip���)�9��m�
���h���Upߘ�8������f/����X�N�3����L����~��R�X�`�&a��n[�����o��������K� "���{��}x;�3w��x�R,���3�I��T�m�<�x�2N+gD%0�c`n!�2dtx�pd*Y��ݷd�T"�D�[ ФH�.`���J�WaB�ݫ�^m�SS�j/Й����wP��!�鱃�&������b�:��ci�I�
�#arIQx�1341~Xy��k�OC?�0 9�u�"�V�P�Im��َ�e:�����a��gs�]ް��=�ӯzrYs�:F���E̺$��@ ڮ*�1�o ���GQ���j�$s��tX�����]�+�t`w�E�}
�~�TP��T�w�W�Q�R��i���`0��j1
��<��^XΟ�E�5FA�M"JM]��,Y��⮏gנ�}��P�pBK)h� 3,��
���a�"=����,��Ψ7[~	%T���c���5��=�.�s�δ^��M;hS��kᜩ�"q8�bv�t���2��77C�v�4&p>��E����^�+e�˫���$�v�4����0���ud 	{������eN��7L�i�=T�E��ޗ���L�#쫧��<;m�tI}�n��[?T߃�F��|�k7�0Z��͸����.V�bKp�n��ir+��V�u&m}���J��>)^�,P�4|�0����1	eZ�JDcF�_(�⸾�S���cLq�!��(W�KJU�:�Y뭮b�<�G>Fu��� ��i�㎶=
�2}����M��z Q|�"E�t�(U/��P����=Tqϔ��1�����.\��?d�����߸u����ΧD�<{��Ơ=)���	�AM�IIGȌ�^��,_�MSR{o�O�v��U��Șɓx�]=ٗ�(Kj�g!`J/���*��Ue+�����Ĥ�MV�$��{��8
�>$����\0��ǝ;{��A��ރtu�S낓G[�h��Q����	X�}��;�wt���ղ��q?�2�8��������f��Ȑ�׭�P=},`��#s���jv��ӓI����>�Z���qV�C:8賈iExW���7�+�`9&�s��*�zs�
���R�w`�P���>Q�ϩ�	p�A�xA�\9=;G\c�ÊΊ1���ai�և���@[�}@ܫ �� X�����l�0�P� M��k|���9�g���	�YB@	GY�t�	=_%��#�rx�c�j�§��,�{���k6��^s�I�)���|�y�GN�5���h?[e�=�KYaa5忁'B����*bd軏�0���C�J6&)9�����DW䲈���B��g:}u	�ϜѢ�="�+T(MX����,��.��:A�B(�п,a��t��T� ꔚ�ff��Rb9�,i�^�Ķ��:O(p����ULh�OI�S�)��@2�1�6��|66��f�7�`�����Y�%�m��"�
S2w^���<��G�T��ȫ�
�`���nd΀�
���J�֌Wŷ��ӊ52���y�O:���؃
�$���?^e�?�'�ϝҒ/��(=_i�T���a{x�ri�����2ȶl�b����Sf0�-8��U�>'��� �ᩤ�
e�B8ϝ/�Ӽ��zW멑q�� ��X?4��.ƗYN�2��Zv�q�9^B�bϛtP���\\{!%@	J�_�,�J$v�tg��l/] �@�9<؃���'�
����aK:��$�F�_���D��D�SXw�Z�l�H��fE&��o~�kw�D�U���z;�^�����ǰҥ���,�����y�EV�~�'3�nB2g���G7��h*0�G��P�,�`��ՠۼ�sh�]������5���6��T��v�a�������Z9�,f�cX�AZ�hW]k�Z�E�l6̓mX7*����*#5�xS�	�w`�&
��<18J�XB����_�Ge� r.��Sv��uXif_wj����+W�E"$���9����̒PQZ����z;\Li�r�(-*P�\R�\��UWk���Gm�i��;���q �|���2*!�/k"!
�>��5���ij	{��(z~���� �n �a�z�OY���*�#v�@PRB���i��I%�*�(�"Qf7䮙QFCRGq�(��ݨ7C�kn�V�^��"��"gŨxV���T���)?��i�Nz   ',.�Z��6�x�9��y��gn���Gs�Ľ�`���2ڃ��
�v�v�*���������(#�h_��Q
c�)����͝�X|wIT��E�F�;+���/�|���ϰ9����F>ȏp}���OSs�ǌ��h2(X �(�U�	3��w��ky��Ѹ
��d� ��\LR�9��
+�КF�E�u�u	���r�՚'�%�\�{)�z��P�0ֵȯZ;�/�>�X9Ϣ��_�K������YN���)ݔ�f�&p��>�}+u�������T@a��R�}7=Ū��fz]��))�ȑ��kAy����gӡ�*�DH�Xc��s����O��D��I��o �Ԑ6L��t�&�AJ�¶C���b�r�&�8����sC���b\����F��du�b��Uy]���Ë�Q(��N���s�~鞫G�xD�3��[�
�ó����8�P���9m����{��Eucq�{�I+�w�y�氣$��۶:�	:��M:�7�����CPE��N�D������M����S�i���C�-&BI{�.Mһ?�1��>�|�g��Xt=u-$#\���zr�4�i�W��*�\��Av�+ɠ�'��Q�9�X);�R�/�V���ʝޕ{9,̵!(�`�4,��g�@�T�|�F�P9y�m��2�N�2jX�g�ڇ$"� 	��&��pA���$�F`�R�	�n���d�X[�rk��
M��l�p�cč
���7���`�-�Eh�(=�iƼ <0��b��P�C�n���˜l�U��#b9G�-t`�f\'��Us��)>m���|�#��җ[��R��^�����b��X�RvN�م���B�ɐ>#k��V�U��U��p͗���%z�=~��Fաf��-��Q ����n����R�ApK?S�Id�� �m�EmS�ތ�7�{����ݛqzOZ5�޼_�gԅ�͸���q�޴�����8|�x�a;O�!/�p�a�BR��Ƌ���A�xq�G�]�Z��q���,:d܆q�CCY�����P�FFj�[)H5����GIۜv�#q���he���������`�$R�x8���J���H���G0�c@zeӚ}s�JF��&��<z�>{8���G&84��5kX��a�U\��a���:\�v��[����-suj
h��l�<svg width="49" height="50" viewBox="0 0 49 50" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<rect x="0.0811768" y="0.429688" width="48.7233" height="48.9408" rx="12" fill="url(#pattern0_14432_6014)"/>
<defs>
<pattern id="pattern0_14432_6014" patternContentUnits="objectBoundingBox" width="1" height="1">
<use xlink:href="#image0_14432_6014" transform="matrix(0.00446429 0 0 0.00444444 -0.0022321 0)"/>
</pattern>
<image id="image0_14432_6014" width="225" height="225" xlink:href="data:image/png;base64,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   X  X      �ژ#K �                      X      8x�Y  8 �Y  ��   4  �  $�#K ��gv0\�G���i�8u             �HT1(   �T� K   �X� K �H   ; ; Microsoft.Windows.Update.Orchestrator  sPOω�G�����v�     �       DownloadUpdatesActivity PartA_PrivTags 
wilActivity �threadId __TlgCV__ downloadUpdateList networkCostPolicy listType          4  oo48RsrwikapKr6n.1 { 4 5 9 6 a 6 2 2 - 9 0 5 b - 4 8 7 6 - b b 7 f - c 4 8 b 7 b 6 2 2 6 a d } : F e a t u r e   u p d a t e   t o   W i n d o w s   1 0 ,   v e r s i o n   2 2 H 2 , { 2 2 d f 4 7 3 b - 8 0 c 7 - 4 9 6 2 - 9 4 2 9 - e 9 b e 7 6 3 5 8 0 c e } : A S U S   -   S y s t e m   -   8 / 7 / 2 0 1 9   1 2 : 0 0 : 0 0   A M   -   1 . 0 . 0 . 1 2 , { 0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b } : R e a l t e k   S e m i c o n d u c t o r   C o r p .   -   A u d i o   D e v i c e ,   O t h e r   h a r d w a r e   -   R e a l t e k   H i g h   D e f i n i t i o n   A u d i o , { b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b } : G o o g l e ,   I n c .   -   O t h e r   h a r d w a r e   -   A n d r o i d   B o o t l o a d e r   I n t e r f a c e , { 2 0 5 9 5 7 5 f - 8 e 0 5 - 4 2 b 3 - 9 b 3 0 - 9 c a c 4 b 5 f 4 9 4 d } : S e c u r i t y   I n t e l l i g e n c e   U p d a t e   f o r   M i c r o s o f t   D e f e n d e r   A n t i v i r u s   -   K B 2 2 6 7 6 0 2   ( V e r s i o n   1 . 4 1 9 . 5 1 0 . 0 )   -   C u r r e n t   C h a n n e l   ( B r o a d ) , { d 6 c 5 9 2 b 9 - 4 3 b 7 - 4 a 9 0 - 9 5 b 0 - 1 a 8 1 c 3 d f 2 a 2 1 } : W i n d o w s   M a l i c i o u s   S o f t w a r e   R e m o v a l   T o o l   x 6 4   -   v 5 . 1 2 9   ( K B 8 9 0 8 3 0 ) , { 3 2 7 3 e 6 f 1 - 6 5 3 2 - 4 4 7 3 - b a 2 2 - 4 d 6 5 6 c b b 7 4 a e } : 2 0 2 0 - 1 1   C u m u l a t i v e   U p d a t e   f o r   . N E T   F r a m e w o r k   3 . 5   a n d   4 . 8   f o r   W i n d o w s   1 0   V e r s i o n   1 9 0 3   f o r   x 6 4   ( K B 4 5 8 0 9 8 0 )   U n r e s t r i c t e d   N o r m a l    h ��   4  �  �x�#K ��gv0\�G���i�8u              >��1(   �T� K   �X� K �H   ; ; Microsoft.Windows.Update.Orchestrator  sPOω�G�����v�        		 ActivityError PartA_PrivTags 
wilActivity �hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage                �onecore\enduser\windowsupdate\muse\orchestrator\common\lib\taskmanager.cpp P  usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity    1��   4  �  �x�#K ��gv0\�G���i�8u           @  �1(   �T� K   �X� K �H   ; ; Microsoft.Windows.Update.Orchestrator  sPOω�G�����v�     8   ,, ActivityError PartA_PrivTags 
wilActivity �hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage failureId failureCount function             �onecore\enduser\windowsupdate\muse\orchestrator\common\lib\taskmanager.cpp P  usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity          2�   4  �  �ژ#K ��gv0\�G���i�8u           @  ���4(                   H   ; ; Microsoft.Windows.Update.Orchestrator  sPOω�G�����v�     �    � �  TaskModified PartA_PrivTags 
wuDeviceid TaskName NextRuntime LastRuntime LastResult �ModificationType TaskState MissedRuns WakeToRun �ActionPath ActionArg               �0��E��@�o5�u��A C   P o w e r   D o w n l o a d   k          �
     (         % s y s t e m r o o t % \ s y s t e m 3 2 \ u s o c l i e n t . e x e   S t a r t D o w n l o a d   V e r������������������������������������������������������������������������������������������������������������������������������������������������������������������������   8         N!�*K �                      8      8�Y  8x�Y   �   4  �  �ژ#K ��gv0\�G���i�8u              Ho�4(                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     @    7 7  Running download NetworkCostPolicy UpdateListType  U n r e s t r i c t e d   N o r m a l   ��   4  �  �ژ#K ��gv0\�G���i�8u              +�4(   �T� K   �X� K �H   ; ; Microsoft.Windows.Update.Orchestrator  sPOω�G�����v�        		 ActivityError PartA_PrivTags 
wilActivity �hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage               �$�onecore\enduser\windowsupdate\muse\orchestrator\core\updatehandlers\wuupdatehandlers\downloadhandler.cpp    usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity    t h��   4  �  �ژ#K ��gv0\�G���i�8u           @  �k�4(   �T� K   �X� K �H   ; ; Microsoft.Windows.Update.Orchestrator  sPOω�G�����v�     8   ,, ActivityError PartA_PrivTags 
wilActivity �hresult fileName lineNumber module failureType message threadId callContext originatingContextId originatingContextName originatingContextMessage currentContextId currentContextName currentContextMessage failureId failureCount function            �$�onecore\enduser\windowsupdate\muse\orchestrator\core\updatehandlers\wuupdatehandlers\downloadhandler.cpp    usocoreworker.exe      4  \DownloadActivity\DownloadUpdatesActivity    DownloadActivity      DownloadUpdatesActivity          o u�   4  �  ���#K ��gv0\�G���i�8u              f6�Q(                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     (       Info Message message        G e t R e s e r v e M a n a g e r L o a d e r   r e s u l t :   0 x 0 0 0 0 0 0 0 0     �   4  �  ���#K ��gv0\�G���i�8u           @  DR(                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     P    G G 
UpdatePromotedBySerialization PartA_PrivTags 
wuDeviceid updateId         �0��E��@�o5�u��"��E[�vH�ċ{b&��   4  �  N!�*K ��gv0\�G���i�8u              ��.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     (       Info Message message        U s i n g   r e s e r v e   s p a c e   f o r   d o w n l o a d   ��������   4  �  N!�*K ��gv0\�G���i�8u           �  r��.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        eg4odfjeWUSvEgcs.1.3.2        "��E[�vH�ċ{b&�           �0��E��@�o5�u��   R S : 2 0 C F C   ���������   4  �  N!�*K ��gv0\�G���i�8u           �  n��.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.6.11        ;G�"ǀbI�)�v5��           �0��E��@�o5�u��   R D : 2 F E A 7   ��������   4  �  N!�*K ��gv0\�G���i�8u           �  �`�.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.5.11        FH�%d@�z
t�?�           �0��E��@�o5�u��   0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b   ��������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������   �  �      �Z�*K �                      �      8x�Y  8 �Y  ��   4  �  N!�*K ��gv0\�G���i�8u           �   .                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.4.12        YB���B��8F���           �0��E��@�o5�u��   b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b    { 2 2��   4  �  N!�*K ��gv0\�G���i�8u           �  �J.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        eg4odfjeWUSvEgcs.1.1.3        ���ַC�J�����*!�           �0��E��@�o5�u��   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0   6 a 6 a��   4  �  N!�*K ��gv0\�G���i�8u           �  b)i.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.1.17        ��s22esD�"Mel�t��           �0��E��@�o5�u��   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0    0 - 9�   4  �  N!�*K ��gv0\�G���i�8u              ��.                   H   ; ; Microsoft.Windows.Update.Orchestrator  sPOω�G�����v�     (       Info Message message        A c t i v e   h o u r s   p o l i c i e s   c h e c k   c o m p l e t e d .    t4�   4  �  ���*K ��gv0\�G���i�8u              z\8.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     (       Info Message message        U s o S e s s i o n   u p d a t i n g   d o w n l o a d   w a i t   e n t e r e d   m i n u t e s   t o   2 9 8 5    ; ��   4  �  �Z�*K ��gv0\�G���i�8u           @  ��0.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � �  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive �wuDeviceid updateScenarioType flightID errorCode �UpdateStatus updateState networkCostPolicy             eg4odfjeWUSvEgcs.1.3.3 "��E[�vH�ċ{b&�    �0��E��@�o5�u��   R S : 2 0 C F C                 xe   ��   4  �  �Z�*K ��gv0\�G���i�8u           @  �r1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � �  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive �wuDeviceid updateScenarioType flightID errorCode �UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.6.12 ;G�"ǀbI�)�v5��    �0��E��@�o5�u��   R D : 2 F E A 7                 gCont� �   4  �  �Z�*K ��gv0\�G���i�8u              ��1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     P    F F  Regulated update(s) to download were found in non-interactive mode   ��   4  �  �Z�*K ��gv0\�G���i�8u           �  ��1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.5.12       FH�%d@�z
t�?�           �0��E��@�o5�u��   0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b   D o w ����������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������   �  �      �
�*K �                      �      8�Y  8x�Y  ��   4  �  �Z�*K ��gv0\�G���i�8u           @  N�1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � �  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive �wuDeviceid updateScenarioType flightID errorCode �UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.5.13 FH�%d@�z
t�?�    �0��E��@�o5�u��   0 d 1 a 4 8 4 6 - 2 5 c a - 4 0 6 4 - 8 9 7 a - 0 a 7 4 8 7 3 f 1 a b b   �$�          reTyp��   4  �  ��*K ��gv0\�G���i�8u           @  j*1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � �  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive �wuDeviceid updateScenarioType flightID errorCode �UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.4.13 YB���B��8F���    �0��E��@�o5�u��   b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b                   ; �   4  �  ��*K ��gv0\�G���i�8u           @  ��31.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � �  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive �wuDeviceid updateScenarioType flightID errorCode �UpdateStatus updateState networkCostPolicy             eg4odfjeWUSvEgcs.1.2.7 _WY ��B�0��K_IM�    �0��E��@�o5�u��   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0                      �   4  �  ��*K ��gv0\�G���i�8u           @  �<1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � �  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive �wuDeviceid updateScenarioType flightID errorCode �UpdateStatus updateState networkCostPolicy             eg4odfjeWUSvEgcs.1.1.4 ���ַC�J�����*!�    �0��E��@�o5�u��   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0                 rator �   4  �  ��*K ��gv0\�G���i�8u           @  �sD1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � �  Progress PartA_PrivTags 
__TlgCV__ updateId revisionNumber interactive �wuDeviceid updateScenarioType flightID errorCode �UpdateStatus updateState networkCostPolicy             bcFoB7ONF0WNz6Br.1.1.18 ��s22esD�"Mel�t��    �0��E��@�o5�u��   F I D : 0 0 0 0 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0                 .Updab�   4  �  ��*K ��gv0\�G���i�8u              ��G1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     (       Info Message message        D o w n l o a d   P r o g r e s s :   p r o g r e s s S t a t e = 1 ,   P e r c e n t   c o m p l e t e   =   0 ,   t o t a l B y t e s T o D o w n l o a d = 7   �i�8u4�   4  �  ��*K ��gv0\�G���i�8u              �4u1.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     (       Info Message message        U s o S e s s i o n   u p d a t i n g   d o w n l o a d   w a i t   e n t e r e d   m i n u t e s   t o   2 9 8 5   .11 � �   4  �  �
�*K ��gv0\�G���i�8u              ؘ�=.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     P    F F  Regulated update(s) to download were found in non-interactive mode   ��   4  �  �
�*K ��gv0\�G���i�8u           �  2g�=.                   H   ; ; Microsoft.Windows.Update.Orchestrator  :��.DJ��R"�.��     �    � � 	Download PartA_PrivTags 
__TlgCV__ eventScenario deferReason updateId revisionNumber interactive errorCode �wuDeviceid updateScenarioType flightID        bcFoB7ONF0WNz6Br.1.4.14       YB���B��8F���           �0��E��@�o5�u��   b 3 4 2 0 7 5 9 - a 8 e a - 4 2 d 6 - 9 e d d - 0 4 3 8 4 6 a 6 a 0 a b   ������������������������������������������������������      casing: null,
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