# 1.0

### Breaking changes:

- The `Checks` interface changed completely. Now we have to implement `getMatches(): MatchCollection` and `processMatch(mixed $match, FileMatch $file): array<ViolationBuilder>`.
- The `ViolationBuilder` has a different interface now. Now the file, check and identifier are optional fields, and will be set by the CheckRunner if they were left empty in the processMatch method. </br>
The `build` method now requires the Check and the FileMatch.
