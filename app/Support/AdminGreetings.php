<?php

namespace App\Support;

/** One randomly-picked line shown in a welcome-back modal right after an admin logs in — see AuthenticatedSessionController::finishLogin(). */
class AdminGreetings
{
    public const MESSAGES = [
        "Johnny, every great website you've built started with someone who refused to give up on the details — that's you.",
        'Small, consistent progress today, Johnny, builds the platform your clients will brag about tomorrow.',
        "Johnny, you're not just managing a business — you're building trust, one client at a time.",
        'The best work happens when you show up, Johnny, even on the days it feels routine.',
        "Behind every \"Launched\" project is a leader who cared enough to get it right — that's been you, Johnny.",
        "Great support isn't glamorous, Johnny, but it's exactly what's kept VisionBridge's clients coming back.",
        "Today's small fixes are tomorrow's five-star reviews, Johnny — keep going.",
        "Discipline beats motivation, Johnny — and you've built a business proving it.",
        'Every client who chose VisionBridge, Johnny, chose it because of the standard you set.',
        'Progress, not perfection, Johnny. Ship it, then make it better.',
        "This dashboard doesn't run itself, Johnny — thank you for showing up and running it well.",
        'A calm, organized leader makes for a confident, happy client. That\'s exactly who you are, Johnny.',
        "You can't pour from an empty cup, Johnny — take care of yourself while you take care of everyone else.",
        'Excellence is built in the unglamorous, unseen moments, Johnny. This login is one of them.',
        'Every "Completed" status set today, Johnny, is a promise you kept.',
        "Good systems, run by good people, build great businesses — and Johnny, you're both.",
        'The details you check today, Johnny, are the trust your clients feel tomorrow.',
        'Momentum is built one login, one task, one decision at a time, Johnny — and you keep showing up.',
        "Whatever's on today's list, Johnny — you've handled harder days than this.",
        "Welcome back, Johnny. The mission continues, and you're exactly the right person to carry it.",
        'Quiet consistency outperforms occasional brilliance, Johnny — keep showing up.',
        'Your clients never see the hours behind the scenes, Johnny, but they always feel the care in them.',
        'A well-run backend is the quietest kind of excellence — and yours is exactly that, Johnny.',
        "You don't need a perfect day, Johnny — just a productive one.",
        "Every reply you send, Johnny, is a small deposit into a client's trust in VisionBridge.",
        "Reliability is a skill, Johnny — you've been practicing it since day one.",
        'The best leaders make hard work look effortless. That\'s you today, Johnny.',
        'One well-handled request can turn a client into an advocate for life, Johnny.',
        'Slow and steady still builds fast-growing businesses, Johnny — yours is proof.',
        "Today doesn't have to be extraordinary, Johnny — it just has to be handled well.",
        'The work you do behind this dashboard, Johnny, is the reason someone else\'s business runs smoothly.',
        'Good habits, repeated daily, are what "professional" actually looks like — and that\'s you, Johnny.',
        "You're allowed to be proud of a normal, well-executed day, Johnny.",
        'Every client onboarded is a story that started with you believing in the process, Johnny.',
        "Systems don't fail people, Johnny — people forget to trust the systems. Trust yours today.",
        'A single clear update from you, Johnny, can save a client from a week of worry.',
        "The best leaders aren't the busiest, Johnny — they're the most intentional. That's you.",
        'Consistency is the quiet superpower behind VisionBridge, Johnny — and it starts with you.',
        'What feels routine to you, Johnny, might be the exact reassurance a client needed today.',
        "Every project has its slow chapters, Johnny — they're still part of VisionBridge's success story.",
        "You're not behind, Johnny. You're building at the pace real, lasting things get built.",
        'Care shows up in small things, Johnny: a clear message, a timely update, a kept promise.',
        "The work is worth doing well, Johnny, even on the days no one's watching closely.",
        "Growth is rarely loud, Johnny — it's usually just today's tasks, done properly, again.",
        'A calm response to a stressful message is a skill your clients will never forget, Johnny.',
        "Every account you manage, Johnny, represents someone's real hope for their business.",
        'Good work compounds, Johnny — what you handle well today makes tomorrow easier.',
        "You've built more trust today than you'll ever get credit for, Johnny. That's alright — it still counts.",
        "The strongest businesses are run by people who don't need applause to do it right, Johnny — that's you.",
        'However today goes, Johnny, you\'re still the right person showing up to run VisionBridge.',
    ];

    public static function random(): string
    {
        return self::MESSAGES[array_rand(self::MESSAGES)];
    }
}
