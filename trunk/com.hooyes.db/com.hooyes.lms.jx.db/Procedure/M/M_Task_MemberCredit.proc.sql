-- DROP PROC [M_Task_MemberCredit]
GO
-- =============================================
-- Version:     1.0.0.8
-- Author:		hooyes
-- Create date: 2012-04-25
-- Update date: 2014-01-10
-- Desc:  jx
-- =============================================
CREATE PROCEDURE [dbo].[M_Task_MemberCredit] @count INT = 100
AS 
    DECLARE @MID INT ,
        @Year INT ,
        @Type INT ,
        @ID INT ,
        @score INT = 0 ,
        @int_order_id INT,
		@Flag_Year int  = 2013
    DECLARE MCursor CURSOR LOCAL STATIC
    FOR
        SELECT TOP ( @count )
                M.MID ,
                MC.Year ,
                M.Type ,
                MC.ID
        FROM    MemberCredit MC
                INNER JOIN Member M ON MC.IDCard = M.IDCard
        WHERE   MC.flag = 0
        ORDER BY MC.ID

    OPEN MCursor 
    FETCH NEXT FROM MCursor INTO @MID, @Year, @Type, @ID
    WHILE ( @@FETCH_STATUS = 0 ) 
        BEGIN
            /* 订单与产品 */
            IF NOT EXISTS ( SELECT  1
                            FROM    My_Products
                            WHERE   MID = @MID
                                    AND PID = @Year ) 
                BEGIN
                
                    EXECUTE [Get_Seed] @ID = 4, @Value = @int_order_id OUTPUT
                    INSERT  INTO [dbo].[Orders]
                            ( [ID] ,
                              [MID] ,
                              [OrderID] ,
                              [Amount] ,
                              [Cash] ,
                              [Credit] ,
                              [Status] ,
                              [Tags] ,
                              [CreateDate] ,
                              [UpdateDate] ,
                              [Memo]
                            )
                            SELECT  [ID] = @int_order_id ,
                                    [MID] = @MID ,
                                    [OrderID] = 0 ,
                                    [Amount] = CASE WHEN @Year >= @Flag_Year
                                                    THEN 44
                                                    ELSE 60
                                               END ,
                                    [Cash] = 0 ,
                                    [Credit] = CASE WHEN @Year >= @Flag_Year
                                                    THEN 44
                                                    ELSE 60
                                               END ,
                                    [Status] = 10 ,
                                    [Tags] = ( SELECT   ID
                                               FROM     dbo.Products
                                               WHERE    PID = @Year
                                             ) ,
                                    [CreateDate] = GETDATE() ,
                                    [UpdateDate] = GETDATE() ,
                                    [Memo] = 'C'        
                    INSERT  INTO [dbo].[My_Products]
                            ( [MID] ,
                              [PID] ,
                              [CreateDate] ,
                              [Memo]
									
                            )
                            SELECT  [MID] = @MID ,
                                    [PID] = @Year ,
                                    [CreateDate] = GETDATE() ,
                                    [Memo] = 'C'
                    
                END
		    /* 课时 */
            IF NOT EXISTS ( SELECT  1
                            FROM    dbo.Report
                            WHERE   MID = @MID
                                    AND [Year] = @Year
                                    AND Minutes >= 1080 ) 
                BEGIN
	
                    IF @Year = 2013 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 13001
                            EXECUTE [M_Update_Courses] @MID, 13002
                            EXECUTE [M_Update_Courses] @MID, 13003
                            EXECUTE [M_Update_Courses] @MID, 13009
                            EXECUTE [M_Update_Courses] @MID, 13010
                        END    
                    IF @Year = 2012 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 12001
                            EXECUTE [M_Update_Courses] @MID, 12002
                            EXECUTE [M_Update_Courses] @MID, 12003
                            EXECUTE [M_Update_Courses] @MID, 12004
                            EXECUTE [M_Update_Courses] @MID, 12005
                            EXECUTE [M_Update_Courses] @MID, 12006
                        END
                    IF @Year = 2011 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 11001
                            EXECUTE [M_Update_Courses] @MID, 11002
                            EXECUTE [M_Update_Courses] @MID, 11003
                   
                        END
                    IF @Year = 2010 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 10001
                            EXECUTE [M_Update_Courses] @MID, 10002
                        END
                    IF @Year = 2009 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 9001
                            EXECUTE [M_Update_Courses] @MID, 9002
                            EXECUTE [M_Update_Courses] @MID, 9003
                        END
                    IF @Year = 2008 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 8001
                            EXECUTE [M_Update_Courses] @MID, 8002
                        END
                    IF @Year = 2007 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 7001
                            EXECUTE [M_Update_Courses] @MID, 7002
                            EXECUTE [M_Update_Courses] @MID, 7003
                        END
                    IF @Year = 2006 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 6001
                            EXECUTE [M_Update_Courses] @MID, 6002
                            EXECUTE [M_Update_Courses] @MID, 6003
                            EXECUTE [M_Update_Courses] @MID, 6004
                        END
  
                    IF @Year = 2005 
                        BEGIN
                            EXECUTE [M_Update_Courses] @MID, 5001
                            EXECUTE [M_Update_Courses] @MID, 5002
                            EXECUTE [M_Update_Courses] @MID, 5003
                        END                      
  
                                       
                END 
	        /* 成绩 */
            IF NOT EXISTS ( SELECT  1
                            FROM    Report
                            WHERE   MID = @MID
                                    AND [Year] = @Year
                                    AND Score >= 60 ) 
                BEGIN

                    SET @score = 65
                    SELECT  @score = @score + RAND() * 21

                    EXECUTE [Update_Report] @MID = @MID, @Year = @Year,
                        @score = @score, @Status = 1

                    UPDATE  dbo.Report
                    SET     CommitDate = GETDATE()
                    WHERE   MID = @MID
                            AND Year = @Year 
                END
            EXECUTE [Task_EvaluteCourses] @MID = @MID, @Year = @Year   
            UPDATE  MemberCredit
            SET     flag = 1 ,
                    MID = @MID
            WHERE   ID = @ID

            FETCH NEXT FROM MCursor INTO @MID, @Year, @Type, @ID
        END
    CLOSE MCursor 
    DEALLOCATE MCursor 
    RETURN 0