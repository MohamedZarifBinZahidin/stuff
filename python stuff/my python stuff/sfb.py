def has_same_finger_bigram(word, column_letters):
    for i in range(len(word) - 1):
        bigram = word[i:i+2]
        for col in column_letters:
            if bigram[0] in col and bigram[1] in col:
                if bigram[0] == bigram[1]:
                    # print(False)
                    return False
                else:
                    # print(True)
                    return(True)
    # print(False)
    return False

def words_with_sfb(words, sfb_set):
    sfb_words = []
    for word in words:
        if has_same_finger_bigram(word, sfb_set):
            sfb_words.append(word)
    return sfb_words

# List of words to check
word_list = [
    "the", "be", "of", "and", "a", "to", "in", "he", "have", "it", "that", "for", "they", "with", "as", "not",
    "on", "she", "at", "by", "this", "we", "you", "do", "but", "from", "or", "which", "one", "would", "all",
    "will", "there", "say", "who", "make", "when", "can", "more", "if", "no", "man", "out", "other", "so",
    "what", "time", "up", "go", "about", "than", "into", "could", "state", "only", "new", "year", "some",
    "take", "come", "these", "know", "see", "use", "get", "like", "then", "first", "any", "work", "now",
    "may", "such", "give", "over", "think", "most", "even", "find", "day", "also", "after", "way", "many",
    "must", "look", "before", "great", "back", "through", "long", "where", "much", "should", "well", "people",
    "down", "own", "just", "because", "good", "each", "those", "feel", "seem", "how", "high", "too", "place",
    "little", "world", "very", "still", "nation", "hand", "old", "life", "tell", "write", "become", "here",
    "show", "house", "both", "between", "need", "mean", "call", "develop", "under", "last", "right", "move",
    "thing", "general", "school", "never", "same", "another", "begin", "while", "number", "part", "turn",
    "real", "leave", "might", "want", "point", "form", "off", "child", "few", "small", "since", "against",
    "ask", "late", "home", "interest", "large", "person", "end", "open", "public", "follow", "during",
    "present", "without", "again", "hold", "govern", "around", "possible", "head", "consider", "word",
    "program", "problem", "however", "lead", "system", "order", "eye", "plan", "run", "keep", "face",
    "fact", "group", "play", "stand", "increase", "early", "course", "change", "help", "line"
]


# Define a set of same-finger bigrams (customize as needed)
keyboard_columns = [
    ['q', 'a', 'z'],
    ['w', 's', 'x'],
    ['e', 'd', 'c'],
    ['r', 'f', 'v'],
    ['t', 'g', 'b'],
    ['y', 'h', 'n'],
    ['u', 'j', 'm'],
    ['i', 'k', ','],
    ['o', 'l', '.'],
    ['p', ';', '/'],
]

keyboard_columns_colemak= [
    ["q", "a", "z"],
    ["w", "r", "x"],
    ["f", "s", "c"],
    ["p", "t", "v"],
    ["g", "d", "b"],
    ["j", "h", "k"],
    ["l", "n", "m"],
    ["u", "e", ","],
    ["y", "i", "."],
    [";", "o", "/"],
]

# Get words with same-finger bigrams
sfb_word_list = words_with_sfb(word_list, keyboard_columns)
print("Words with same-finger bigrams:", sfb_word_list)
print("Number of words with same-finger bigrams:", len(sfb_word_list))

sfb_word_list = words_with_sfb(word_list, keyboard_columns_colemak)
print("Words with same-finger bigrams:", sfb_word_list)
print("Number of words with same-finger bigrams:", len(sfb_word_list))
