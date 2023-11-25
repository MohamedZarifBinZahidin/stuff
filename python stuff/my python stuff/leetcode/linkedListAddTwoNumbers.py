# Definition for singly-linked list.
# class ListNode:
#     def __init__(self, val=0, next=None):
#         self.val = val
#         self.next = next
class Solution:
    def addTwoNumbers(self, l1: Optional[ListNode], l2: Optional[ListNode]) -> Optional[ListNode]:
        
        result = []
        totalDigits = int
        carryOver = 0

        if len(l1) > len(l2):
            totalDigits = len(l1)
            shortestList = l2      
        else:
            totalDigits = len(l2)
            shortestList = l1



        for digitPair in range(totalDigits):

            if digitPair > len(shortestList) -1 :
                if shortestList == l1:
                    l1.append(0)
                if shortestList == l2:
                    l2.append(0)

            l1Digit = l1[digitPair]
            l2Digit = l2[digitPair]

            

            result.append((l1Digit+l2Digit+carryOver) % 10)

            carryOver = (l1Digit + l2Digit) // 10

        return result

