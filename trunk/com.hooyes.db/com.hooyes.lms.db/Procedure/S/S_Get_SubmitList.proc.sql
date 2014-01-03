-- DROP PROC [S_Get_SubmitList]
GO
-- =============================================
-- Version:     1.0.0.9
-- Author:		hooyes
-- Create date: 2012-07-23
-- Update date: 2013-12-31
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Get_SubmitList] @count INT = 22
AS 
    UPDATE TOP ( 5 )
            Member
    SET     Tag = 0
    WHERE   Tag = 100
            AND DATEDIFF(DAY, regdate, GETDATE()) > 5

    SELECT TOP ( @count )
            M.MID ,
            M.IDSN ,
            M.IDCard ,
            M.RegDate ,
            M.Year ,
            Score = ISNULL(R.Score, 0) ,
            Compulsory = ISNULL(R.Compulsory, 8) ,
            Elective = ISNULL(R.Elective, 18) ,
            Status = ISNULL(R.Status, 0)
    FROM    Member M
            INNER JOIN Report R ON R.MID = M.MID
                                   AND ( R.Status = 0
                                         OR R.Status IS NULL
                                       )
                                   AND R.Minutes >= 1080
                                   AND R.score >= 60
    WHERE   DATEDIFF(HOUR, M.RegDate, GETDATE()) >= 20
            AND ( M.ExpireDate IS NULL
                  OR M.ExpireDate >= GETDATE()
                )
    ORDER BY NEWID()
    RETURN 0