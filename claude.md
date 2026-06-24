# Project Governance Rules — Token & Time Conservation

These rules apply to all Claude Code sessions in this project (VisionBridgeSolutions).
Goal: reduce wasted searches, redundant file reads, and unnecessary token spend.

## 1. File Discovery
- **Do NOT run broad `find`/`grep`/`glob` searches across the whole project.**
- If you need to locate a file and don't already know the path, **stop and ask me** for:
  - the filename or partial filename, OR
  - a keyword/string likely inside the file, OR
  - the relevant folder (e.g. `app/Http/Controllers`, `resources/views/portal`)
- Only search automatically if I've explicitly said "search for it yourself" in that message.

## 2. Scope of Searches (when search is approved)
- Never search `vendor/`, `node_modules/`, `storage/framework`, `storage/logs`, `.git/`, `public/build`.
- Search the narrowest folder possible first (e.g. `resources/views/portal`) before falling back to the full app directory.
- Use one targeted search, not multiple overlapping `find` commands in parallel.

## 3. File Reading
- Don't re-read a file you've already read earlier in the same session unless it may have changed.
- When editing, read only the relevant section/lines, not the entire file, if the file is large (>300 lines).

## 4. Before Big Actions
- Before running any multi-step exploration (more than 2 bash/search/read calls), give me a one-line plan first and wait for a go-ahead if the task is ambiguous.
- If a task can be done with a direct edit (I gave you the file), skip discovery entirely and go straight to editing.

## 5. Communication Style
- Keep status updates brief — no need to narrate every intermediate step in detail.
- Summarize changes at the end in a short bullet list, not a full walkthrough, unless I ask for details.

## 6. When I Provide Context
- If I attach a file or paste a path, treat that as the source of truth — do not independently verify by searching elsewhere unless something looks broken.

## 7. Ask, Don't Assume
- If a request is ambiguous (e.g. "fix the upload path"), ask me which file/module before touching anything, rather than guessing and exploring.

## 8. FEATURES.md Sync
- After any code change (edit, fix, new feature), check whether it warrants an entry in FEATURES.md (e.g. new feature, behavior change, new config/env var, removed functionality).
- Minor internal fixes, refactors, or typo corrections do not need an entry.
- If it does qualify, update FEATURES.md as part of the same response — don't ask permission first, just do it and mention it in the summary.
- If unsure whether a change is "feature-worthy," default to asking me rather than skipping it silently.

## 10. File Editing Method
- When reading or writing files, use direct file read/edit tools only.
- **Do NOT write Python scripts, shell scripts, or one-off code** to read, parse, modify, or generate files (e.g. no `python3 -c "..."`, no temporary `.py`/`.sh` helper scripts) unless I explicitly ask for a script as the deliverable.
- This applies to all file types — PHP, Blade, JS, CSS, JSON, env files, etc. Edit them directly.
- Scripts add unnecessary processing time and token overhead for simple read/write tasks — go straight to the file.


## 9. Git Commit Message
- After completing any code change (edit, fix, new feature), always end your response with a ready-to-use commit message in this format:
- - Keep the message concise (under ~72 characters for the summary line) and written in imperative mood (e.g. "Fix upload path", "Add pending payment nav indicator").
- If the change touches multiple unrelated things, suggest splitting into multiple commits and give a commit message for each.
- Do not run `git commit` or `git push` yourself — just provide the command for me to run manually.