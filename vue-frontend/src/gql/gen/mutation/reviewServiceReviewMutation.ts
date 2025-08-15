import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, ReviewServiceReviewResult} from "../schema"

import {MutationReviewServiceReviewArgs} from "../schema"

export type MutationReviewServiceReviewVars = MutationReviewServiceReviewArgs

export function reviewServiceReviewMutation(mutation: MutationGenqlSelection['review_service_review'] | null) {            
    return {
      build: (variables: MutationReviewServiceReviewVars) => genqlBuilder.build(mutation ? {review_service_review: mutation} : {__scalar: true}, {review_service_review: variables}),  
      mutate: (variables: MutationReviewServiceReviewVars) => ({} as Promise<ReviewServiceReviewResult>),
      _variables: {} as MutationReviewServiceReviewVars,
      _result: {} as ReviewServiceReviewResult,
    }
}

export type MutationReviewServiceReview = {
  builder: (variables: MutationReviewServiceReviewVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['review_service_review'],
  variables: MutationReviewServiceReviewVars,
  result: ReviewServiceReviewResult
}

export default reviewServiceReviewMutation