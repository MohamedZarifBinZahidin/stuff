class Solution:
    def setZeroes(self, matrix: List[List[int]]) -> None:
        """
        Do not return anything, modify matrix in-place instead.
        """
        # locationsOfZeros = List[List[int]]
        rowsWithZeroes = []
        columnsWithZeroes = []

        # for rowIndex in range(len(matrix)):
        #     row = matrix[rowIndex]

        for rowIndex,row in enumerate(matrix):

            # for columnIndex in range(len(row)):
                
            for columnIndex,number in enumerate(row):
                
                # if row[columnIndex] == 0:
                if number == 0:
                    # aLocationOfAZero = [row,x]
                    # locationsOfZeros.append(aLocationOfAZero)
                    if rowIndex not in rowsWithZeroes:
                        rowsWithZeroes.append(rowIndex)

                    if columnIndex not in columnsWithZeroes:
                        columnsWithZeroes.append(columnIndex)

        print(rowsWithZeroes)
        print(columnsWithZeroes)


        for aRowWithZeroes in rowsWithZeroes:
            row = matrix[aRowWithZeroes]
            for index,number in enumerate(row):
                row[index] = 0

        # so weird this doesn't work 

        # for aRowWithZeroes in rowsWithZeroes:
        #     row = matrix[aRowWithZeroes]
        #     for number in row:
        #         number = 0
        
        # for rowIndex in range(len(matrix)):
        #     row = matrix[rowIndex]

        for row in matrix:
            for aColumnWithZeroes in columnsWithZeroes:
                row[aColumnWithZeroes] = 0
                


                