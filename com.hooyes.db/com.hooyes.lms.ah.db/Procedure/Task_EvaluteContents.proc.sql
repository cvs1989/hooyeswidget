-- =============================================
-- Version:     1.0.0.3
-- Author:		hooyes
-- Create date: 2012-02-18
-- Update date: 2013-10-05
-- Desc: 评估一门课程是否可以取得学时
-- =============================================
CREATE PROCEDURE [dbo].[Task_EvaluteContents] 
    @MID INT, 
	@CID INT
AS 
    DECLARE @count1 INT = 0 ,
        @count2 INT = 0
	/* 完成所有章节 @count1 = 0 */
	/*
		SELECT @count1 = COUNT(1) FROM (
			SELECT c.CID
				,c.CCID
				,c.Name
				,c.Url
				,Minutes = ISNULL(myc.Minutes,0)
				,Second = ISNULL(myc.Second,0)
				,Status = ISNULL(myc.Status,0) 
			FROM (SELECT CID,CCID,Name,Url FROM Contents WHERE CID = @CID)
			as c
				LEFT OUTER JOIN
				(SELECT CID,CCID,MID,[Minutes],[Second],[Status] FROM My_Contents WHERE MID = @MID and CID = @CID)
			as myc
			ON myc.CCID = c.CCID
		) as co
		WHERE Status = 0 
	*/
	/* 分钟数已完成 @count2 > 0 */
    SELECT  @count2 = COUNT(1)
    FROM    My_Courses myc
            INNER JOIN Courses c ON c.CID = myc.CID
    WHERE   myc.MID = @MID
            AND myc.CID = @CID
            AND ( myc.Minutes >= c.ActMinutes
                  OR myc.Minutes >= c.Length * 45
                )


	/* 满足此2条件，课程完成 */

    IF @count1 = 0
        AND @count2 > 0 
        BEGIN
            UPDATE  My_Courses
            SET     [Status] = 1
            WHERE   CID = @CID
                    AND MID = @MID 
					AND [Status] = 0
        END

    RETURN 0