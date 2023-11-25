# best algo i can give
class Solution(object):
    def threeSum(self, nums):
        #         :type nums: List[int]
        #         :rtype: List[List[int]]

        # optimization somehow
        nums.sort()

        print(nums)

        listOfLists = []
        offset1 = 1
        offset2 = 1
        offset3 = 0  # for z index to decrement , after y incremented

        for x in range(0, len(nums) - 2):
            num1 = nums[x]
            if x > 0:  # if after strating position, then only check
                if num1 == nums[x - 1]:
                    offset1 += 1
                    offset2 = 1
                    print(nums[x])
                    continue

            if nums[x] > 0:  # if positive
                x = len(nums) - 1  # end everything

            for y in range(offset1, len(nums) - 1):
                num2 = nums[y]
                if y > offset1:  # if after strating position, then only check
                    if (
                        num2 == nums[y - 1]
                    ):  # no need to check every array in listOfLists, because its already sorted
                        offset2 += 1
                        continue

                

                for z in range(len(nums) - 1 - offset3, offset1 + offset2 - 1, -1):
                    num3 = nums[z]
                    if z < len(nums) - 1:
                        if num3 == nums[z + 1]:
                            continue

                    if nums[x] + nums[y] + nums[z] < 0 or z < y:
                        break

                    

                    if num1 + num2 + num3 == 0:
                        listofNumbers = [num1, num2, num3]
                        listofNumbers.sort()

                        if listofNumbers not in listOfLists:
                            listOfLists.append(listofNumbers)

                        # to decrement downwards
                        offset3 = len(nums) - z

                        # skips this loop
                        # z = offset1 + offset2
                        break


                    if 0 < nums[x] + nums[y] + nums[z] or z < y:
                        # to decrement downwards
                        offset3 = len(nums) - z # after checking sum is positive, we update offset3 so it doesn't recheck sum w/ numbers higher than current num[z],
                        # tho after adding this, only still 311/312 tests passed, but still improvement, i give up
                        continue
                
                

                offset2 += 1

            offset1 += 1
            offset2 = 1
            offset3 = 0

        return listOfLists

    def threeSum2(self, nums):
        res = []
        nums.sort()

        for i, a in enumerate(nums):
            if i > 0 and a == nums[i - 1]:
                continue

            l, r = i + 1, len(nums) - 1
            while l < r:
                threeSum = a + nums[l] + nums[r]
                if threeSum > 0:
                    r -= 1
                elif threeSum < 0:
                    l += 1
                else:
                    res.append([a, nums[l], nums[r]])
                    l += 1
                    while nums[l] == nums[l - 1] and l < r:
                        l += 1
        return res


# this codes works but not efficient enough

# class Solution(object):
#     def threeSum(self, nums):


# #         :type nums: List[int]
# #         :rtype: List[List[int]]


#         listOfLists = []
#         offset1 = 1
#         offset2 = 1

#         for x in range(0,len(nums)  -2):
#             print(x)
#             num1 = nums[x]

#             print("offset1" , offset1)
#             for y in range(offset1 ,len(nums)   -1):

#                 num2 = nums[y]
#                 # print("num2 is")
#                 # print(num2)


#                 for z in range(offset1 + offset2 ,len(nums)):

#                     num3 = nums[z]

#                     listofNumbers = [num1,num2,num3]
#                     # listofNumbers.sort()
#                     print(listofNumbers)

#                     # print("hello")
#                     # print(x)
#                     # print(y)

#                     if num1 + num2 + num3 == 0 :
#                         listofNumbers = [num1,num2,num3]
#                         listofNumbers.sort()
#                         # print(listofNumbers)

#                         if listofNumbers not in listOfLists:

#                             listOfLists.append(listofNumbers)

#                 offset2 +=1

#             offset1 +=1
#             offset2 =1
#             # print("offset1 is")
#             # print(offset1)
#             # print(num1)


#         return listOfLists
