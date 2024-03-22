arr = [3, 5, 2, 1, 1, 7, 2]
cnt = [0] * 11

for num in arr:
    cnt[num] += 1

for i in range(1, 11):
    for _ in range(cnt[i]):
        print(i, end="")
