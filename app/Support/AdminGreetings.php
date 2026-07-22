<?php

namespace App\Support;

/** One randomly-picked line shown in a welcome-back modal right after an admin logs in — see AuthenticatedSessionController::finishLogin(). */
class AdminGreetings
{
    public const MESSAGES = [
        'Every great website starts with someone who refused to give up on the details.',
        "Small, consistent progress today builds the platform clients will brag about tomorrow.",
        "You're not just managing projects — you're building trust, one deadline at a time.",
        'The best work happens when you show up, even on the days it feels routine.',
        "Behind every \"Launched\" status is a team that cared enough to get it right.",
        'Great support isn\'t glamorous, but it\'s exactly what keeps clients coming back.',
        "Today's small fixes are tomorrow's five-star reviews.",
        "Discipline beats motivation — and you've got both.",
        'Every client request is a chance to prove why they chose VisionBridge.',
        'Progress, not perfection. Ship it, then make it better.',
        "The dashboard doesn't run itself — thank you for showing up and running it well.",
        'A calm, organized admin makes for a confident, happy client.',
        "You can't pour from an empty cup — take care of yourself while taking care of the business.",
        'Excellence is built in the unglamorous, unseen moments. This is one of them.',
        "Every \"Completed\" status you set today is a promise kept.",
        "Good systems, run by good people, build great businesses. You're both.",
        "The details you check today are the trust clients feel tomorrow.",
        'Momentum is built one login, one task, one decision at a time.',
        "Whatever's on today's list — you've handled harder days than this.",
        "Welcome back. The mission continues, and you're exactly the right person to carry it.",
        'Quiet consistency outperforms occasional brilliance — keep showing up.',
        'The client never sees the hours behind the scenes, but they always feel the care in them.',
        'A well-run backend is the quietest kind of excellence.',
        "You don't need a perfect day to make real progress — just a productive one.",
        "Every reply you send is a small deposit into a client's trust account.",
        "Reliability is a skill. You've been practicing it every day you log in.",
        'The best teams make hard work look effortless — that\'s you today.',
        'One well-handled request can turn a client into an advocate for life.',
        'Slow and steady still builds fast-growing businesses.',
        "Today doesn't have to be extraordinary — it just has to be handled well.",
        "The work you do behind this dashboard is the reason someone else's business runs smoothly.",
        'Good habits, repeated daily, are what "professional" actually looks like.',
        "You're allowed to be proud of a normal, well-executed day.",
        'Every client onboarded is a story that started with someone believing in the process.',
        'Systems don\'t fail people — people forget to trust the systems. Trust yours today.',
        'A single clear update can save a client from a week of worry.',
        "The best admins aren't the busiest — they're the most intentional.",
        'Consistency is the quiet superpower behind every trusted brand.',
        "What feels routine to you might be the exact reassurance a client needed today.",
        "Every project has its slow chapters — they're still part of the success story.",
        "You're not behind. You're building at the pace real, lasting things get built.",
        'Care shows up in small things: a clear message, a timely update, a kept promise.',
        "The work is worth doing well, even on the days no one's watching closely.",
        'Growth is rarely loud — it\'s usually just today\'s tasks, done properly, again.',
        "A calm response to a stressful message is a skill clients will never forget.",
        "Every account you manage represents someone's real hope for their business.",
        "Good work compounds — what you handle well today makes tomorrow easier.",
        "You've built more trust today than you'll ever get credit for. That's alright — it still counts.",
        "The strongest businesses are run by people who don't need applause to do it right.",
        "However today goes, you're still the right person showing up to run it.",
    ];

    public static function random(): string
    {
        return self::MESSAGES[array_rand(self::MESSAGES)];
    }
}
