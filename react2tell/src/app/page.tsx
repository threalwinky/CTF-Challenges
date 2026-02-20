"use client";

import { useState } from "react";

const stories: Record<string, string[]> = {
  "😊": [
    "Hello nice to meet you! Today I had the most wonderful conversation with an old friend.",
    "I am very happy! I just got accepted into my dream university!",
    "What a beautiful sunny day! Everything feels perfect right now.",
    "I received a surprise gift from my family today. My heart is full of joy!",
    "Just finished a great workout and feeling amazing!"
  ],
  "😐": [
    "It's just another ordinary day, nothing special happening.",
    "Feeling neutral about everything right now. Not good, not bad.",
    "The weather is okay, my mood is okay, everything is just... okay.",
    "Going through the motions of daily life without much emotion.",
    "Meh. That's pretty much how I feel today."
  ],
  "😠": [
    "I'm so frustrated! Someone cut in line at the coffee shop today.",
    "The traffic was terrible and made me late for an important meeting!",
    "My internet has been down all day and I can't get any work done!",
    "Why do people not respond to important messages? It's so annoying!",
    "I'm really angry about all the unfairness in the world right now."
  ],
  "😏": [
    "I just won an argument with facts and logic. Feeling pretty clever!",
    "Pulled off a perfect surprise party that no one saw coming.",
    "I knew exactly what was going to happen, and I was right all along.",
    "Just delivered the perfect comeback. Nailed it!",
    "That smug feeling when your predictions come true."
  ],
  "😄": [
    "Hahaha! Just watched the funniest video ever!",
    "I can't stop laughing at the joke my friend told me!",
    "Everything is hilarious today! Life is so fun!",
    "My cat just did the silliest thing and I'm crying with laughter!",
    "Comedy show tonight was absolutely brilliant! My face hurts from smiling!"
  ]
};

export default function Home() {
  const [currentStory, setCurrentStory] = useState<string>("");
  const [currentEmotion, setCurrentEmotion] = useState<string>("");

  const handleEmotion = (emotion: string) => {
    const emotionStories = stories[emotion];
    if (emotionStories && emotionStories.length > 0) {
      const randomStory = emotionStories[Math.floor(Math.random() * emotionStories.length)];
      setCurrentStory(randomStory);
      setCurrentEmotion(emotion);
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 p-4">
      <main className="w-full max-w-4xl">
        <h1 className="text-4xl font-bold text-center mb-2 text-gray-800">
          React2tell
        </h1>
        <p className="text-center text-lg text-gray-600 mb-12">
          React to us and we will tell you.
        </p>
        
        <div className="flex justify-center gap-4 mb-12 flex-wrap">
          {Object.keys(stories).map((emotion) => (
            <button
              key={emotion}
              onClick={() => handleEmotion(emotion)}
              className={`text-6xl p-4 rounded-full transition-all hover:scale-110 hover:shadow-lg cursor-pointer ${ 
                currentEmotion === emotion ? 'bg-white shadow-xl scale-110' : 'bg-white/50 hover:bg-white'
              }`}
              title={`Click for ${emotion} stories`}
            >
              {emotion}
            </button>
          ))}
        </div>

        <div className="bg-white rounded-2xl shadow-2xl p-8 min-h-[300px] border-4 border-gray-800">
          {currentStory ? (
            <div className="space-y-4">
              <div className="flex items-center gap-3">
                <span className="text-5xl">{currentEmotion}</span>
              </div>
              <p className="text-xl text-gray-700 leading-relaxed">
                {currentStory}
              </p>
            </div>
          ) : (
            <div></div>
          )}
        </div>
      </main>
    </div>
  );
}