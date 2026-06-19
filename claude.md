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